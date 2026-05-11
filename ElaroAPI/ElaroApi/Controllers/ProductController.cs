using Microsoft.AspNetCore.Mvc;
using ElaroApi.Data;
using ElaroApi.Models;
using Microsoft.EntityFrameworkCore;

namespace ElaroApi.Controllers
{
    [ApiController]
    [Route("api/[controller]")]
    public class ProductController : ControllerBase
    {
        private readonly AppDbContext _context;

        public ProductController(AppDbContext context)
        {
            _context = context;
        }

        // GET: api/product/all
        [HttpGet("all")]
        public async Task<IActionResult> GetAllProducts()
        {
            var urunler = await _context.Urunler.ToListAsync();
            return Ok(new
            {
                success = true,
                data = urunler
            });
        }

        // GET: api/product/5
        [HttpGet("{id}")]
        public async Task<IActionResult> GetProductById(int id)
        {
            var product = await _context.Urunler.FindAsync(id);

            if (product == null)
                return NotFound(new { message = "Ürün bulunamadı." });

            return Ok(product);
        }
    }
}
