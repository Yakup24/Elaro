using Microsoft.AspNetCore.Mvc;
using ElaroApi.Data;
using ElaroApi.Models;
using Microsoft.AspNetCore.Identity;
using Microsoft.AspNetCore.RateLimiting;
using Microsoft.EntityFrameworkCore;
using ElaroApi.Services;

namespace ElaroApi.Controllers
{
    [ApiController]
    [Route("api/[controller]")]
    [EnableRateLimiting("Auth")]
    public class YoneticiController : ControllerBase
    {
        private readonly AppDbContext _context;
        private readonly PasswordHasher<Yonetici> _passwordHasher = new();
        private readonly JwtTokenService _jwtTokenService;

        public YoneticiController(AppDbContext context, JwtTokenService jwtTokenService)
        {
            _context = context;
            _jwtTokenService = jwtTokenService;
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

            if (dogrulama == PasswordVerificationResult.Failed)
                return Unauthorized(new { message = "Geçersiz kullanıcı adı veya şifre." });

            if (dogrulama == PasswordVerificationResult.SuccessRehashNeeded)
            {
                yonetici.Sifre = _passwordHasher.HashPassword(yonetici, request.Sifre);
                await _context.SaveChangesAsync();
            }

            var token = _jwtTokenService.CreateAdminToken(yonetici);

            return Ok(new
            {
                yonetici.YoneticiID,
                yonetici.AdSoyad,
                yonetici.Email,
                accessToken = token.AccessToken,
                expiresAt = token.ExpiresAt
            });
        }
    }

    public class YoneticiLoginRequest
    {
        public string KullaniciAdi { get; set; } = string.Empty;
        public string Sifre { get; set; } = string.Empty;
    }
}
