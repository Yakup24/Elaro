using Microsoft.AspNetCore.Mvc;
using ElaroApi.Data;
using ElaroApi.Models;
using Microsoft.EntityFrameworkCore;
using System.Threading.Tasks;

namespace ElaroApi.Controllers
{
    [ApiController]
    [Route("api/[controller]")]
    public class UrunController : ControllerBase
    {
        private readonly AppDbContext _context;

        public UrunController(AppDbContext context)
        {
            _context = context;
        }

        [HttpGet]
        public async Task<IActionResult> GetUrunler()
        {
            try
            {
                var urunler = await _context.Urunler.ToListAsync();
                return Ok(urunler);
            }
            catch (Exception ex)
            {
                Console.Error.WriteLine(ex);
                return StatusCode(500, new { message = "Sunucu hatası" });
            }
        }
    }
}
