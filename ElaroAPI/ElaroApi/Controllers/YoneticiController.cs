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

            var passwordCheck = VerifyPassword(yonetici, request.Sifre);

            if (!passwordCheck.IsValid)
                return Unauthorized(new { message = "Geçersiz kullanıcı adı veya şifre." });

            if (passwordCheck.RequiresRehash)
            {
                yonetici.Sifre = BCrypt.Net.BCrypt.HashPassword(request.Sifre);
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

        private static PasswordCheckResult VerifyPassword(Yonetici yonetici, string password)
        {
            try
            {
                if (BCrypt.Net.BCrypt.Verify(password, yonetici.Sifre))
                {
                    return new PasswordCheckResult(true, false);
                }
            }
            catch
            {
            }

            var legacyHasher = new PasswordHasher<Yonetici>();
            var legacyResult = legacyHasher.VerifyHashedPassword(yonetici, yonetici.Sifre, password);

            return legacyResult switch
            {
                PasswordVerificationResult.Success => new PasswordCheckResult(true, true),
                PasswordVerificationResult.SuccessRehashNeeded => new PasswordCheckResult(true, true),
                _ => new PasswordCheckResult(false, false)
            };
        }

        private sealed record PasswordCheckResult(bool IsValid, bool RequiresRehash);
    }

    public class YoneticiLoginRequest
    {
        public string KullaniciAdi { get; set; } = string.Empty;
        public string Sifre { get; set; } = string.Empty;
    }
}
