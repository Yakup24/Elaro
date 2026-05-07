using Microsoft.AspNetCore.Mvc;
using ElaroApi.Data;
using ElaroApi.Models;
using Microsoft.EntityFrameworkCore;
using System.Text.Json.Serialization;

namespace ElaroApi.Controllers
{
    [ApiController]
    [Route("api/[controller]")]
    public class AuthController : ControllerBase
    {
        private readonly AppDbContext _context;

        public AuthController(AppDbContext context)
        {
            _context = context;
        }

        // ✅ POST: api/auth/register
        [HttpPost("register")]
        public async Task<IActionResult> Register([FromBody] Musteri musteri)
        {
            try
            {
                // E-posta zaten kayıtlı mı?
                var mevcutKullanici = await _context.Musteriler
                    .FirstOrDefaultAsync(u => u.Eposta == musteri.Eposta);

                if (mevcutKullanici != null)
                {
                    return BadRequest(new { message = "Bu e-posta zaten kayıtlı." });
                }

                // Kayıt tarihi sistemden alınıyor
                musteri.KayitTarihi = DateTime.Now;

                _context.Musteriler.Add(musteri);
                await _context.SaveChangesAsync();

                return Ok(new { message = "Kayıt başarılı." });
            }
            catch (Exception ex)
            {
                // Detaylı hata döndür (geliştirme süresince kullan)
                return StatusCode(500, new
                {
                    message = "Sunucu hatası oluştu.",
                    hata = ex.Message,
                    detay = ex.InnerException?.Message
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
                    .FirstOrDefaultAsync(u => u.Eposta == istek.Eposta && u.Sifre == istek.Sifre);

                if (kullanici == null)
                    return Unauthorized(new { message = "Geçersiz e-posta veya şifre." });

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
                return StatusCode(500, new
                {
                    message = "Sunucu hatası.",
                    hata = ex.Message
                });
            }
        }
    }

    // DTO: Giriş İsteği
    public class GirisIstegi
    {
        [JsonPropertyName("Eposta")]
        public string Eposta { get; set; }

        [JsonPropertyName("Sifre")]
        public string Sifre { get; set; }
    }
}
