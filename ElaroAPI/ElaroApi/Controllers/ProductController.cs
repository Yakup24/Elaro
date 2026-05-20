using ElaroApi.DTOs;
using ElaroApi.Services;
using Microsoft.AspNetCore.Mvc;

namespace ElaroApi.Controllers;

[ApiController]
[Route("api/[controller]")]
public sealed class ProductController : ControllerBase
{
    private readonly IProductService _productService;

    public ProductController(IProductService productService)
    {
        _productService = productService;
    }

    // GET: api/product/all
    [HttpGet("all")]
    [ProducesResponseType(typeof(IReadOnlyList<ProductDto>), StatusCodes.Status200OK)]
    public async Task<IActionResult> GetAllProducts(
        [FromQuery] int page = 1,
        [FromQuery] int pageSize = 50,
        CancellationToken cancellationToken = default)
    {
        var products = await _productService.GetPagedProductsAsync(page, pageSize, cancellationToken);
        return Ok(products);
    }

    // GET: api/product/5
    [HttpGet("{id:int}")]
    [ProducesResponseType(typeof(ProductDto), StatusCodes.Status200OK)]
    [ProducesResponseType(StatusCodes.Status404NotFound)]
    public async Task<IActionResult> GetProductById(
        int id,
        CancellationToken cancellationToken = default)
    {
        var product = await _productService.GetProductByIdAsync(id, cancellationToken);

        if (product == null)
        {
            return NotFound(new { message = "Product was not found." });
        }

        return Ok(product);
    }
}
