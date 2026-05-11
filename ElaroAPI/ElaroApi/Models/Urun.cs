using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;

namespace ElaroApi.Models
{
    [Table("Urun2")]
    public class Urun
    {
        [Key]
        public int UrunID { get; set; }

        [Column("Adi")]
        public string Ad { get; set; }

        [Column("Aciklama")]
        public string Aciklama { get; set; }

        [Column("Fiyat")]
        public decimal Fiyat { get; set; }

        [Column("StokAdedi")]
        public int StokAdedi { get; set; }

        [Column("KategoriID")]
        public int KategoriID { get; set; }

        [Column("Marka")]
        public string Marka { get; set; }

        [Column("Renk")]
        public string Renk { get; set; }

        [Column("GorselURL")]
        public string GorselURL { get; set; }
    }
}
