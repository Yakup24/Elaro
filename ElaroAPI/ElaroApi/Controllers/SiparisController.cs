using Microsoft.AspNetCore.Mvc;
using ElaroApi.Data;
using ElaroApi.Models;
using Microsoft.EntityFrameworkCore;

namespace ElaroApi.Controllers
{
    [ApiController]
    [Route("api/[controller]")]
    public class SiparisController : ControllerBase
    {
        private readonly AppDbContext _context;

        public SiparisController(AppDbContext context)
        {
            _context = context;
        }

        // GET: api/siparis
        [HttpGet]
        public async Task<IActionResult> GetAllOrders([FromQuery] int? musteriId)
        {
            if (musteriId == null)
                return BadRequest(new { message = "Siparis listesi icin musteriId gereklidir." });

            var siparisler = await _context.Siparisler
                .Where(s => s.MusteriID == musteriId.Value)
                .Select(s => ToSafeResponse(s))
                .ToListAsync();

            return Ok(siparisler);
        }

        // GET: api/siparis/5
        [HttpGet("{id}")]
        public async Task<IActionResult> GetOrderById(int id)
        {
            var siparis = await _context.Siparisler
                .FirstOrDefaultAsync(s => s.SiparisID == id);

            if (siparis == null)
                return NotFound(new { message = "Siparis bulunamadı." });

            return Ok(ToSafeResponse(siparis));
        }

        // POST: api/siparis
        [HttpPost]
        public async Task<IActionResult> CreateOrder([FromBody] Siparis yeniSiparis)
        {
            yeniSiparis.SiparisTarihi = DateTime.Now;
            _context.Siparisler.Add(yeniSiparis);
            await _context.SaveChangesAsync();

            return Ok(new { message = "Siparis başarıyla oluşturuldu." });
        }

        private static object ToSafeResponse(Siparis siparis)
        {
            return new
            {
                siparis.SiparisID,
                siparis.MusteriID,
                siparis.SiparisTarihi,
                siparis.ToplamTutar,
                siparis.OdemeYontemi,
                siparis.SiparisDurumu
            };
        }
    }
}
