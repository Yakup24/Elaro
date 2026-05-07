using Microsoft.AspNetCore.Mvc;
using ElaroApi.Data;
using ElaroApi.Models;
using Microsoft.EntityFrameworkCore;

namespace ElaroApi.Controllers
{
    [ApiController]
    [Route("api/[controller]")]
    public class KategoriController : ControllerBase
    {
        private readonly AppDbContext _context;

        public KategoriController(AppDbContext context)
        {
            _context = context;
        }

        // GET: api/kategori
        [HttpGet]
        public async Task<IActionResult> GetAllCategories()
        {
            var kategoriler = await _context.Kategoriler.ToListAsync();
            return Ok(kategoriler);
        }

        // GET: api/kategori/5
        [HttpGet("{id}")]
        public async Task<IActionResult> GetCategoryById(int id)
        {
            var kategori = await _context.Kategoriler.FindAsync(id);
            if (kategori == null)
                return NotFound(new { message = "Kategori bulunamadı." });

            return Ok(kategori);
        }
    }
}
