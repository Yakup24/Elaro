using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;

namespace ElaroApi.Models
{
    [Table("Yonetici2")]
    public class Yonetici
    {
        [Key]
        public int YoneticiID { get; set; }

        [Column("KullaniciAdi")]
        public string KullaniciAdi { get; set; }

        [Column("Sifre")]
        public string Sifre { get; set; }

        [Column("AdSoyad")]
        public string AdSoyad { get; set; }

        [Column("Email")]
        public string Email { get; set; }
    }
}
