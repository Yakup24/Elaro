using Microsoft.AspNetCore.Mvc;
using ElaroApi.Data;
using ElaroApi.Models;
using Microsoft.EntityFrameworkCore;

namespace ElaroApi.Controllers
{
    [ApiController]
    [Route("api/[controller]")]
    public class AdresController : ControllerBase
    {
        private readonly AppDbContext _context;

        public AdresController(AppDbContext context)
        {
            _context = context;
        }

        // GET: api/adres
        [HttpGet]
        public async Task<IActionResult> GetAllAddresses()
        {
            var adresler = await _context.Adresler.ToListAsync();
            return Ok(adresler);
        }

        // GET: api/adres/5
        [HttpGet("{id}")]
        public async Task<IActionResult> GetAddressById(int id)
        {
            var adres = await _context.Adresler.FindAsync(id);
            if (adres == null)
                return NotFound(new { message = "Adres bulunamadı." });

            return Ok(adres);
        }

        // POST: api/adres
        [HttpPost]
        public async Task<IActionResult> CreateAddress([FromBody] Adres yeniAdres)
        {
            _context.Adresler.Add(yeniAdres);
            await _context.SaveChangesAsync();

            return Ok(new { message = "Adres başarıyla kaydedildi." });
        }
    }
}
