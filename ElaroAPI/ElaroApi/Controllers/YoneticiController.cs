using Microsoft.AspNetCore.Mvc;
using ElaroApi.Data;
using ElaroApi.Models;
using Microsoft.AspNetCore.Identity;
using Microsoft.AspNetCore.RateLimiting;
using Microsoft.EntityFrameworkCore;

namespace ElaroApi.Controllers
{
    [ApiController]
    [Route("api/[controller]")]
    [EnableRateLimiting("Auth")]
    public class YoneticiController : ControllerBase
    {
        private readonly AppDbContext _context;
        private readonly PasswordHasher<Yonetici> _passwordHasher = new();

        public YoneticiController(AppDbContext context)
        {
            _context = context;
        }

        // GET: api/yonetici
        [HttpGet]
        public IActionResult GetAll()
        {
            return NotFound(new { message = "Yonetici listesi public API'de kapali." });
        }

        // POST: api/yonetici/login
        [HttpPost("login")]
        public async Task<IActionResult> Login([FromBody] YoneticiLoginRequest request)
        {
            var yonetici = await _context.Yoneticiler
                .FirstOrDefaultAsync(y => y.KullaniciAdi == request.KullaniciAdi);

            if (yonetici == null)
                return Unauthorized(new { message = "Geçersiz kullanıcı adı veya şifre." });

            var dogrulama = _passwordHasher.VerifyHashedPassword(yonetici, yonetici.Sifre, request.Sifre);
            var eskiDuzMetinSifre = dogrulama == PasswordVerificationResult.Failed && yonetici.Sifre == request.Sifre;

            if (dogrulama == PasswordVerificationResult.Failed && !eskiDuzMetinSifre)
                return Unauthorized(new { message = "Geçersiz kullanıcı adı veya şifre." });

            if (eskiDuzMetinSifre || dogrulama == PasswordVerificationResult.SuccessRehashNeeded)
            {
                yonetici.Sifre = _passwordHasher.HashPassword(yonetici, request.Sifre);
                await _context.SaveChangesAsync();
            }

            return Ok(new
            {
                yonetici.YoneticiID,
                yonetici.AdSoyad,
                yonetici.Email
            });
        }
    }

    public class YoneticiLoginRequest
    {
        public string KullaniciAdi { get; set; } = string.Empty;
        public string Sifre { get; set; } = string.Empty;
    }
}
