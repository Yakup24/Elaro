using Microsoft.AspNetCore.Mvc;
using ElaroApi.Data;
using ElaroApi.Models;
using Microsoft.EntityFrameworkCore;

namespace ElaroApi.Controllers
{
    [ApiController]
    [Route("api/[controller]")]
    public class YoneticiController : ControllerBase
    {
        private readonly AppDbContext _context;

        public YoneticiController(AppDbContext context)
        {
            _context = context;
        }

        // GET: api/yonetici
        [HttpGet]
        public async Task<IActionResult> GetAll()
        {
            var yoneticiler = await _context.Yoneticiler.ToListAsync();
            return Ok(yoneticiler);
        }

        // POST: api/yonetici/login
        [HttpPost("login")]
        public async Task<IActionResult> Login([FromBody] YoneticiLoginRequest request)
        {
            var yonetici = await _context.Yoneticiler
                .FirstOrDefaultAsync(y => y.KullaniciAdi == request.KullaniciAdi && y.Sifre == request.Sifre);

            if (yonetici == null)
                return Unauthorized(new { message = "Geçersiz kullanıcı adı veya şifre." });

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
        public string KullaniciAdi { get; set; }
        public string Sifre { get; set; }
    }
}
