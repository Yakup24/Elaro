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
        var service = new JwtTokenService(CreateConfiguration());
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

    [Fact]
    public void CreateAdminToken_IncludesAdminClaims()
    {
        var service = new JwtTokenService(CreateConfiguration());
        var response = service.CreateAdminToken(new Yonetici
        {
            YoneticiID = 7,
            KullaniciAdi = "admin",
            AdSoyad = "Demo Admin",
            Email = "admin@example.test",
            Sifre = BCrypt.Net.BCrypt.HashPassword("Password123!")
        });

        var token = new JwtSecurityTokenHandler().ReadJwtToken(response.AccessToken);

        Assert.Equal("Elaro", token.Issuer);
        Assert.Contains("ElaroClients", token.Audiences);
        Assert.Contains(token.Claims, claim =>
            (claim.Type == ClaimTypes.Role || claim.Type == "role") &&
            claim.Value == "Admin");
        Assert.Contains(token.Claims, claim =>
            (claim.Type == ClaimTypes.Email || claim.Type == JwtRegisteredClaimNames.Email || claim.Type == "email") &&
            claim.Value == "admin@example.test");
    }

    [Fact]
    public void Constructor_WithShortJwtKey_ThrowsClearError()
    {
        var configuration = CreateConfiguration();
        configuration["Jwt:Key"] = "too-short";

        var exception = Assert.Throws<InvalidOperationException>(() => new JwtTokenService(configuration));

        Assert.Contains("at least 32 bytes", exception.Message);
    }

    private static ConfigurationManager CreateConfiguration()
    {
        return new ConfigurationManager
        {
            ["Jwt:Issuer"] = "Elaro",
            ["Jwt:Audience"] = "ElaroClients",
            ["Jwt:Key"] = "development_test_key_32_bytes_long",
            ["Jwt:AccessTokenMinutes"] = "30"
        };
    }
}
