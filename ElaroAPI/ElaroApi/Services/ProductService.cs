using ElaroApi.Data;
using ElaroApi.DTOs;
using Microsoft.EntityFrameworkCore;

namespace ElaroApi.Services;

public sealed class ProductService : IProductService
{
    private const int MaxPageSize = 100;
    private readonly AppDbContext _context;

    public ProductService(AppDbContext context)
    {
        _context = context;
    }

    public async Task<IReadOnlyList<ProductDto>> GetPagedProductsAsync(
        int page,
        int pageSize,
        CancellationToken cancellationToken = default)
    {
        var safePage = Math.Max(page, 1);
        var safePageSize = Math.Clamp(pageSize, 1, MaxPageSize);

        return await _context.Urunler
            .AsNoTracking()
            .OrderBy(product => product.UrunID)
            .Skip((safePage - 1) * safePageSize)
            .Take(safePageSize)
            .Select(product => new ProductDto
            {
                UrunID = product.UrunID,
                Ad = product.Ad,
                Aciklama = product.Aciklama,
                Fiyat = product.Fiyat,
                StokAdedi = product.StokAdedi,
                KategoriID = product.KategoriID,
                Marka = product.Marka,
                Renk = product.Renk,
                GorselURL = product.GorselURL
            })
            .ToListAsync(cancellationToken);
    }

    public async Task<ProductDto?> GetProductByIdAsync(
        int id,
        CancellationToken cancellationToken = default)
    {
        return await _context.Urunler
            .AsNoTracking()
            .Where(product => product.UrunID == id)
            .Select(product => new ProductDto
            {
                UrunID = product.UrunID,
                Ad = product.Ad,
                Aciklama = product.Aciklama,
                Fiyat = product.Fiyat,
                StokAdedi = product.StokAdedi,
                KategoriID = product.KategoriID,
                Marka = product.Marka,
                Renk = product.Renk,
                GorselURL = product.GorselURL
            })
            .FirstOrDefaultAsync(cancellationToken);
    }
}
