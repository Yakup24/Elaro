using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;
using System.Text.Json.Serialization;

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
        [JsonIgnore]
        public string Sifre { get; set; }

        [Column("AdSoyad")]
        public string AdSoyad { get; set; }

        [Column("Email")]
        public string Email { get; set; }
    }
}
