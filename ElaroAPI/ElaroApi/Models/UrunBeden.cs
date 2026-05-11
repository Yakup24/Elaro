using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;

namespace ElaroApi.Models
{
    [Table("UrunBeden2")]
    public class UrunBeden
    {
        [Key]
        public int ID { get; set; }

        [Column("UrunID")]
        public int UrunID { get; set; }

        [Column("Beden")]
        public string Beden { get; set; }

        [Column("Stok")]
        public int Stok { get; set; }
    }
}
