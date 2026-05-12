using Microsoft.AspNetCore.Mvc;
using ElaroApi.Data;
using ElaroApi.Models;
using Microsoft.AspNetCore.Identity;
using Microsoft.AspNetCore.RateLimiting;
using Microsoft.EntityFrameworkCore;
using System.Text.Json.Serialization;
using ElaroApi.Services;

namespace ElaroApi.Controllers
{
    [ApiController]
    [Route("api/[controller]")]
    [EnableRateLimiting("Auth")]
    public class AuthController : ControllerBase
    {
        private readonly AppDbContext _context;
        private readonly JwtTokenService _jwtTokenService;

        public AuthController(AppDbContext context, JwtTokenService jwtTokenService)
        {
            _context = context;
            _jwtTokenService = jwtTokenService;
        }

        // ✅ POST: api/auth/register
        [HttpPost("register")]
        public async Task<IActionResult> Register([FromBody] KayitIstegi istek)
        {
            try
            {
                // E-posta zaten kayıtlı mı?
                var mevcutKullanici = await _context.Musteriler
                    .FirstOrDefaultAsync(u => u.Eposta == istek.Eposta);

                if (mevcutKullanici != null)
                {
                    return BadRequest(new { message = "Bu e-posta zaten kayıtlı." });
                }

                var musteri = new Musteri
                {
                    Ad = istek.Ad,
                    Soyad = istek.Soyad,
                    Eposta = istek.Eposta,
                    Telefon = istek.Telefon,
                    Cinsiyet = istek.Cinsiyet,
                    DogumTarihi = istek.DogumTarihi,
                    KayitTarihi = DateTime.Now
                };

                musteri.Sifre = BCrypt.Net.BCrypt.HashPassword(istek.Sifre);

                _context.Musteriler.Add(musteri);
                await _context.SaveChangesAsync();

                return Ok(new { message = "Kayıt başarılı." });
            }
            catch (Exception ex)
            {
                Console.Error.WriteLine(ex);
                return StatusCode(500, new
                {
                    message = "Sunucu hatası oluştu."
                });
            }
        }

        // ✅ POST: api/auth/login
        [HttpPost("login")]
        public async Task<IActionResult> Login([FromBody] GirisIstegi istek)
        {
            try
            {
                var kullanici = await _context.Musteriler
                    .FirstOrDefaultAsync(u => u.Eposta == istek.Eposta);

                if (kullanici == null)
                    return Unauthorized(new { message = "Geçersiz e-posta veya şifre." });

                var passwordCheck = VerifyPassword(kullanici, istek.Sifre);

                if (!passwordCheck.IsValid)
                    return Unauthorized(new { message = "Geçersiz e-posta veya şifre." });

                if (passwordCheck.RequiresRehash)
                {
                    kullanici.Sifre = BCrypt.Net.BCrypt.HashPassword(istek.Sifre);
                    await _context.SaveChangesAsync();
                }

                var token = _jwtTokenService.CreateCustomerToken(kullanici);

                return Ok(new
                {
                    kullanici.MusteriID,
                    kullanici.Ad,
                    kullanici.Soyad,
                    kullanici.Eposta,
                    accessToken = token.AccessToken,
                    expiresAt = token.ExpiresAt
                });
            }
            catch (Exception ex)
            {
                Console.Error.WriteLine(ex);
                return StatusCode(500, new
                {
                    message = "Sunucu hatası."
                });
            }
        }

        private static PasswordCheckResult VerifyPassword(Musteri kullanici, string password)
        {
            try
            {
                if (BCrypt.Net.BCrypt.Verify(password, kullanici.Sifre))
                {
                    return new PasswordCheckResult(true, false);
                }
            }
            catch
            {
            }

            var legacyHasher = new PasswordHasher<Musteri>();
            var legacyResult = legacyHasher.VerifyHashedPassword(kullanici, kullanici.Sifre, password);

            return legacyResult switch
            {
                PasswordVerificationResult.Success => new PasswordCheckResult(true, true),
                PasswordVerificationResult.SuccessRehashNeeded => new PasswordCheckResult(true, true),
                _ => new PasswordCheckResult(false, false)
            };
        }

        private sealed record PasswordCheckResult(bool IsValid, bool RequiresRehash);
    }

    public class KayitIstegi
    {
        [JsonPropertyName("Ad")]
        public string Ad { get; set; } = string.Empty;

        [JsonPropertyName("Soyad")]
        public string Soyad { get; set; } = string.Empty;

        [JsonPropertyName("Eposta")]
        public string Eposta { get; set; } = string.Empty;

        [JsonPropertyName("Telefon")]
        public string? Telefon { get; set; }

        [JsonPropertyName("Sifre")]
        public string Sifre { get; set; } = string.Empty;

        [JsonPropertyName("Cinsiyet")]
        public string? Cinsiyet { get; set; }

        [JsonPropertyName("DogumTarihi")]
        public DateTime? DogumTarihi { get; set; }
    }

    // DTO: Giriş İsteği
    public class GirisIstegi
    {
        [JsonPropertyName("Eposta")]
        public string Eposta { get; set; } = string.Empty;

        [JsonPropertyName("Sifre")]
        public string Sifre { get; set; } = string.Empty;
    }
}
