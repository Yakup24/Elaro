using Microsoft.AspNetCore.Mvc;
using ElaroApi.Data;
using ElaroApi.Models;
using Microsoft.AspNetCore.Authorization;
using Microsoft.EntityFrameworkCore;

namespace ElaroApi.Controllers
{
    [ApiController]
    [Route("api/[controller]")]
    [Authorize(Policy = "CustomerOrAdmin")]
    public class OdemeBilgisiController : ControllerBase
    {
        private readonly AppDbContext _context;

        public OdemeBilgisiController(AppDbContext context)
        {
            _context = context;
        }

        // GET: api/odemeBilgisi
        [HttpGet]
        public async Task<IActionResult> GetAll()
        {
            var odemeler = await _context.OdemeBilgileri
                .Select(o => ToSafeResponse(o))
                .ToListAsync();
            return Ok(odemeler);
        }

        // GET: api/odemeBilgisi/5
        [HttpGet("{id}")]
        public async Task<IActionResult> GetById(int id)
        {
            var odeme = await _context.OdemeBilgileri.FindAsync(id);
            if (odeme == null)
                return NotFound(new { message = "Odeme bilgisi bulunamadı." });

            return Ok(ToSafeResponse(odeme));
        }

        // POST: api/odemeBilgisi
        [HttpPost]
        public async Task<IActionResult> Create([FromBody] OdemeBilgisiCreateRequest request)
        {
            var odeme = new OdemeBilgisi
            {
                MusteriID = request.MusteriID,
                KartAdi = request.KartAdi,
                KartNumarasi = MaskCardNumber(request.KartNumarasi),
                SonKullanmaTarihi = request.SonKullanmaTarihi,
                CVV = string.Empty
            };

            _context.OdemeBilgileri.Add(odeme);
            await _context.SaveChangesAsync();

            return Ok(new { message = "Odeme bilgisi kaydedildi." });
        }

        private static object ToSafeResponse(OdemeBilgisi odeme)
        {
            return new
            {
                odeme.OdemeID,
                odeme.MusteriID,
                odeme.KartAdi,
                KartSonDortHane = LastFourDigits(odeme.KartNumarasi),
                odeme.SonKullanmaTarihi
            };
        }

        private static string MaskCardNumber(string? cardNumber)
        {
            var lastFour = LastFourDigits(cardNumber);
            return string.IsNullOrEmpty(lastFour) ? string.Empty : $"************{lastFour}";
        }

        private static string LastFourDigits(string? value)
        {
            if (string.IsNullOrWhiteSpace(value))
                return string.Empty;

            var digits = new string(value.Where(char.IsDigit).ToArray());
            return digits.Length <= 4 ? digits : digits[^4..];
        }
    }

    public class OdemeBilgisiCreateRequest
    {
        public int MusteriID { get; set; }
        public string KartAdi { get; set; } = string.Empty;
        public string KartNumarasi { get; set; } = string.Empty;
        public string SonKullanmaTarihi { get; set; } = string.Empty;
    }
}
