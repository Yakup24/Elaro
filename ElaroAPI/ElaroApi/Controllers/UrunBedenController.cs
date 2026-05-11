using Microsoft.AspNetCore.Mvc;
using ElaroApi.Data;
using ElaroApi.Models;
using Microsoft.EntityFrameworkCore;

namespace ElaroApi.Controllers
{
    [ApiController]
    [Route("api/[controller]")]
    public class UrunBedenController : ControllerBase
    {
        private readonly AppDbContext _context;

        public UrunBedenController(AppDbContext context)
        {
            _context = context;
        }

        // GET: api/urunbeden
        [HttpGet]
        public async Task<IActionResult> GetAllUrunBedenler()
        {
            var bedenler = await _context.UrunBedenler.ToListAsync();
            return Ok(bedenler);
        }

        // GET: api/urunbeden/5
        [HttpGet("{id}")]
        public async Task<IActionResult> GetById(int id)
        {
            var beden = await _context.UrunBedenler.FindAsync(id);
            if (beden == null)
                return NotFound(new { message = "Ürün beden bilgisi bulunamadı." });

            return Ok(beden);
        }

        // GET: api/urunbeden/byurun/3
        [HttpGet("byurun/{urunId}")]
        public async Task<IActionResult> GetByUrunId(int urunId)
        {
            var bedenler = await _context.UrunBedenler
                .Where(ub => ub.UrunID == urunId)
                .ToListAsync();

            return Ok(bedenler);
        }
    }
}
