using System.Net;
using System.Text.Json;
using Microsoft.AspNetCore.Hosting;
using Microsoft.AspNetCore.Mvc.Testing;
using Microsoft.Extensions.Configuration;

namespace ElaroApi.Tests;

public sealed class HealthEndpointTests : IClassFixture<WebApplicationFactory<Program>>
{
    private readonly WebApplicationFactory<Program> _factory;

    public HealthEndpointTests(WebApplicationFactory<Program> factory)
    {
        _factory = factory;
    }

    [Fact]
    public async Task Health_ReturnsOkPayloadAndSecurityHeaders()
    {
        using var client = CreateClient();

        using var response = await client.GetAsync("/health");

        Assert.Equal(HttpStatusCode.OK, response.StatusCode);
        AssertHeader(response, "X-Content-Type-Options", "nosniff");
        AssertHeader(response, "X-Frame-Options", "DENY");
        AssertHeader(response, "Referrer-Policy", "no-referrer");

        using var document = JsonDocument.Parse(await response.Content.ReadAsStringAsync());
        Assert.Equal("ok", document.RootElement.GetProperty("status").GetString());
        Assert.Equal("ElaroAPI", document.RootElement.GetProperty("service").GetString());
        Assert.True(document.RootElement.TryGetProperty("utc", out _));
    }

    private HttpClient CreateClient()
    {
        return _factory
            .WithWebHostBuilder(builder =>
            {
                builder.UseEnvironment("Development");
                builder.ConfigureAppConfiguration((_, configuration) =>
                {
                    configuration.AddInMemoryCollection(new Dictionary<string, string?>
                    {
                        ["ConnectionStrings:DefaultConnection"] =
                            "Server=(localdb)\\mssqllocaldb;Database=ElaroApiTests;Trusted_Connection=True;TrustServerCertificate=True;",
                        ["Jwt:Issuer"] = "Elaro",
                        ["Jwt:Audience"] = "ElaroClients",
                        ["Jwt:Key"] = "development_test_key_32_bytes_long",
                        ["Jwt:AccessTokenMinutes"] = "30",
                        ["Cors:AllowedOrigins:0"] = "https://localhost",
                        ["RateLimiting:ApiPermitLimit"] = "120",
                        ["RateLimiting:AuthPermitLimit"] = "10"
                    });
                });
            })
            .CreateClient(new WebApplicationFactoryClientOptions
            {
                BaseAddress = new Uri("https://localhost"),
                AllowAutoRedirect = false
            });
    }

    private static void AssertHeader(HttpResponseMessage response, string name, string expectedValue)
    {
        Assert.True(response.Headers.TryGetValues(name, out var values), $"{name} header is missing.");
        Assert.Contains(expectedValue, values);
    }
}
