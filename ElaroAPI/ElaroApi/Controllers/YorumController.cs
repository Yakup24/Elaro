using Microsoft.AspNetCore.Mvc;
using ElaroApi.Data;
using ElaroApi.Models;
using Microsoft.EntityFrameworkCore;

namespace ElaroApi.Controllers
{
    [ApiController]
    [Route("api/[controller]")]
    public class YorumController : ControllerBase
    {
        private readonly AppDbContext _context;

        public YorumController(AppDbContext context)
        {
            _context = context;
        }

        // GET: api/yorum
        [HttpGet]
        public async Task<IActionResult> GetAllComments()
        {
            var yorumlar = await _context.Yorumlar.ToListAsync();
            return Ok(yorumlar);
        }

        // GET: api/yorum/5
        [HttpGet("{id}")]
        public async Task<IActionResult> GetCommentById(int id)
        {
            var yorum = await _context.Yorumlar.FindAsync(id);
            if (yorum == null)
                return NotFound(new { message = "Yorum bulunamadı." });

            return Ok(yorum);
        }

        // POST: api/yorum
        [HttpPost]
        public async Task<IActionResult> CreateComment([FromBody] Yorum yeniYorum)
        {
            yeniYorum.Tarih = DateTime.Now;
            _context.Yorumlar.Add(yeniYorum);
            await _context.SaveChangesAsync();

            return Ok(new { message = "Yorum başarıyla eklendi." });
        }
    }
}
