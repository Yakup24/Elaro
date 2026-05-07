using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;

namespace ElaroApi.Models
{
    [Table("Kategori")]
    public class Kategori
    {
        [Key]
        public int KategoriID { get; set; }

        [Column("Ad")]
        public string Ad { get; set; }

        [Column("Açıklama")]
        public string Açıklama { get; set; }

        [Column("ÜstKategoriID")]
        public int? ÜstKategoriID { get; set; }
    }
}
