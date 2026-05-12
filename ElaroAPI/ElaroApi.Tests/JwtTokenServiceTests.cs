using System.IdentityModel.Tokens.Jwt;
using System.Security.Claims;
using ElaroApi.Models;
using ElaroApi.Services;
using Microsoft.Extensions.Configuration;

namespace ElaroApi.Tests;

public sealed class JwtTokenServiceTests
{
    [Fact]
    public void CreateCustomerToken_IncludesIssuerAudienceAndCustomerClaims()
    {
        var configuration = new ConfigurationManager
        {
            ["Jwt:Issuer"] = "Elaro",
            ["Jwt:Audience"] = "ElaroClients",
            ["Jwt:Key"] = "development_test_key_32_bytes_long",
            ["Jwt:AccessTokenMinutes"] = "30"
        };

        var service = new JwtTokenService(configuration);
        var response = service.CreateCustomerToken(new Musteri
        {
            MusteriID = 42,
            Ad = "Demo",
            Soyad = "User",
            Eposta = "demo@example.test",
            Sifre = BCrypt.Net.BCrypt.HashPassword("Password123!")
        });

        var token = new JwtSecurityTokenHandler().ReadJwtToken(response.AccessToken);

        Assert.Equal("Elaro", token.Issuer);
        Assert.Contains("ElaroClients", token.Audiences);
        Assert.Contains(token.Claims, claim =>
            (claim.Type == ClaimTypes.Role || claim.Type == "role") &&
            claim.Value == "Customer");
        Assert.Contains(token.Claims, claim =>
            (claim.Type == ClaimTypes.Email || claim.Type == JwtRegisteredClaimNames.Email || claim.Type == "email") &&
            claim.Value == "demo@example.test");
        Assert.True(response.ExpiresAt > DateTimeOffset.UtcNow);
    }
}
