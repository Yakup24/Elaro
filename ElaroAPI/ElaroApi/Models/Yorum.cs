using System;
using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;

namespace ElaroApi.Models
{
    [Table("Yorum2")]
    public class Yorum
    {
        [Key]
        public int YorumID { get; set; }

        [Column("MusteriID")]
        public int MusteriID { get; set; }

        [Column("UrunID")]
        public int UrunID { get; set; }

        [Column("Icerik")]
        public string Icerik { get; set; }

        [Column("Puan")]
        public int Puan { get; set; }

        [Column("Tarih")]
        public DateTime Tarih { get; set; }
    }
}
