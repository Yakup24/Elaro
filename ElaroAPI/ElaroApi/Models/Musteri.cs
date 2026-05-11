using System;
using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;
using System.Text.Json.Serialization;

namespace ElaroApi.Models
{
    [Table("Musteri2")]
    public class Musteri
    {
        [Key]
        public int MusteriID { get; set; }

        [Column("Ad")]
        public string Ad { get; set; }

        [Column("Soyad")]
        public string Soyad { get; set; }

        [Column("Eposta")]
        public string Eposta { get; set; }

        [Column("Telefon")]
        public string? Telefon { get; set; }   // Nullable yapıldı

        [Column("Sifre")]
        [JsonIgnore]
        public string Sifre { get; set; }

        [Column("KayitTarihi")]
        public DateTime? KayitTarihi { get; set; }  // Nullable yapıldı

        [Column("Cinsiyet")]
        public string? Cinsiyet { get; set; }   // Nullable yapıldı

        [Column("DogumTarihi")]
        public DateTime? DogumTarihi { get; set; }  // Nullable yapıldı
    }
}
