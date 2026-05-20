using ElaroApi.DTOs;

namespace ElaroApi.Services;

public interface IProductService
{
    Task<IReadOnlyList<ProductDto>> GetPagedProductsAsync(
        int page,
        int pageSize,
        CancellationToken cancellationToken = default);

    Task<ProductDto?> GetProductByIdAsync(
        int id,
        CancellationToken cancellationToken = default);
}
