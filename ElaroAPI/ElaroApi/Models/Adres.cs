using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;

namespace ElaroApi.Models
{
    [Table("Adres2")]
    public class Adres
    {
        [Key]
        public int AdresID { get; set; }

        [Column("MusteriID")]
        public int MusteriID { get; set; }

        [Column("AdresSatiri1")]
        public string AdresSatiri1 { get; set; }

        [Column("AdresSatiri2")]
        public string AdresSatiri2 { get; set; }

        [Column("Il")]
        public string Il { get; set; }

        [Column("Ilce")]
        public string Ilce { get; set; }

        [Column("PostaKodu")]
        public string PostaKodu { get; set; }

        [Column("AcikAdres")]
        public string AcikAdres { get; set; }
    }
}
