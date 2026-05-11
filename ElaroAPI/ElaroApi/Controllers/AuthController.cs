using Microsoft.AspNetCore.Mvc;
using ElaroApi.Data;
using ElaroApi.Models;
using Microsoft.AspNetCore.Identity;
using Microsoft.EntityFrameworkCore;
using System.Text.Json.Serialization;

namespace ElaroApi.Controllers
{
    [ApiController]
    [Route("api/[controller]")]
    public class AuthController : ControllerBase
    {
        private readonly AppDbContext _context;
        private readonly PasswordHasher<Musteri> _passwordHasher = new();

        public AuthController(AppDbContext context)
        {
            _context = context;
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

                musteri.Sifre = _passwordHasher.HashPassword(musteri, istek.Sifre);

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

                var dogrulama = _passwordHasher.VerifyHashedPassword(kullanici, kullanici.Sifre, istek.Sifre);
                var eskiDuzMetinSifre = dogrulama == PasswordVerificationResult.Failed && kullanici.Sifre == istek.Sifre;

                if (dogrulama == PasswordVerificationResult.Failed && !eskiDuzMetinSifre)
                    return Unauthorized(new { message = "Geçersiz e-posta veya şifre." });

                if (eskiDuzMetinSifre || dogrulama == PasswordVerificationResult.SuccessRehashNeeded)
                {
                    kullanici.Sifre = _passwordHasher.HashPassword(kullanici, istek.Sifre);
                    await _context.SaveChangesAsync();
                }

                return Ok(new
                {
                    kullanici.MusteriID,
                    kullanici.Ad,
                    kullanici.Soyad,
                    kullanici.Eposta
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
