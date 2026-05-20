namespace ElaroApi.DTOs;

public sealed class ProductDto
{
    public int UrunID { get; set; }
    public string? Ad { get; set; }
    public string? Aciklama { get; set; }
    public decimal Fiyat { get; set; }
    public int StokAdedi { get; set; }
    public int KategoriID { get; set; }
    public string? Marka { get; set; }
    public string? Renk { get; set; }
    public string? GorselURL { get; set; }
}
