using System.IdentityModel.Tokens.Jwt;
using System.Security.Claims;
using System.Text;
using ElaroApi.Models;
using Microsoft.IdentityModel.Tokens;

namespace ElaroApi.Services;

public sealed class JwtTokenService
{
    private readonly string _issuer;
    private readonly string _audience;
    private readonly SymmetricSecurityKey _signingKey;
    private readonly TimeSpan _lifetime;

    public JwtTokenService(IConfiguration configuration)
    {
        _issuer = configuration["Jwt:Issuer"]
            ?? throw new InvalidOperationException("Jwt:Issuer is not configured.");
        _audience = configuration["Jwt:Audience"]
            ?? throw new InvalidOperationException("Jwt:Audience is not configured.");

        var rawKey = configuration["Jwt:Key"]
            ?? throw new InvalidOperationException("Jwt:Key is not configured.");

        if (Encoding.UTF8.GetByteCount(rawKey) < 32)
        {
            throw new InvalidOperationException("Jwt:Key must be at least 32 bytes.");
        }

        _signingKey = new SymmetricSecurityKey(Encoding.UTF8.GetBytes(rawKey));
        _lifetime = TimeSpan.FromMinutes(configuration.GetValue("Jwt:AccessTokenMinutes", 60));
    }

    public JwtTokenResponse CreateCustomerToken(Musteri musteri)
    {
        var claims = new[]
        {
            new Claim(JwtRegisteredClaimNames.Sub, musteri.MusteriID.ToString()),
            new Claim(JwtRegisteredClaimNames.Email, musteri.Eposta),
            new Claim(ClaimTypes.NameIdentifier, musteri.MusteriID.ToString()),
            new Claim(ClaimTypes.Email, musteri.Eposta),
            new Claim(ClaimTypes.Role, "Customer")
        };

        return CreateToken(claims);
    }

    public JwtTokenResponse CreateAdminToken(Yonetici yonetici)
    {
        var claims = new[]
        {
            new Claim(JwtRegisteredClaimNames.Sub, yonetici.YoneticiID.ToString()),
            new Claim(JwtRegisteredClaimNames.Email, yonetici.Email),
            new Claim(ClaimTypes.NameIdentifier, yonetici.YoneticiID.ToString()),
            new Claim(ClaimTypes.Email, yonetici.Email),
            new Claim(ClaimTypes.Role, "Admin")
        };

        return CreateToken(claims);
    }

    private JwtTokenResponse CreateToken(IEnumerable<Claim> claims)
    {
        var expiresAt = DateTimeOffset.UtcNow.Add(_lifetime);
        var credentials = new SigningCredentials(_signingKey, SecurityAlgorithms.HmacSha256);
        var token = new JwtSecurityToken(
            issuer: _issuer,
            audience: _audience,
            claims: claims.Append(new Claim(JwtRegisteredClaimNames.Jti, Guid.NewGuid().ToString("N"))),
            notBefore: DateTime.UtcNow,
            expires: expiresAt.UtcDateTime,
            signingCredentials: credentials);

        return new JwtTokenResponse(
            new JwtSecurityTokenHandler().WriteToken(token),
            expiresAt);
    }
}

public sealed record JwtTokenResponse(string AccessToken, DateTimeOffset ExpiresAt);
