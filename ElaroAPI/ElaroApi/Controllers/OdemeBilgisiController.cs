using Microsoft.AspNetCore.Mvc;
using ElaroApi.Data;
using ElaroApi.Models;
using Microsoft.EntityFrameworkCore;

namespace ElaroApi.Controllers
{
    [ApiController]
    [Route("api/[controller]")]
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
            var odemeler = await _context.OdemeBilgileri.ToListAsync();
            return Ok(odemeler);
        }

        // GET: api/odemeBilgisi/5
        [HttpGet("{id}")]
        public async Task<IActionResult> GetById(int id)
        {
            var odeme = await _context.OdemeBilgileri.FindAsync(id);
            if (odeme == null)
                return NotFound(new { message = "Odeme bilgisi bulunamadı." });

            return Ok(odeme);
        }

        // POST: api/odemeBilgisi
        [HttpPost]
        public async Task<IActionResult> Create([FromBody] OdemeBilgisi odeme)
        {
            _context.OdemeBilgileri.Add(odeme);
            await _context.SaveChangesAsync();

            return Ok(new { message = "Odeme bilgisi kaydedildi." });
        }
    }
}
