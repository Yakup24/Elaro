using System;
using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;

namespace ElaroApi.Models
{
    [Table("Siparis2")]
    public class Siparis
    {
        [Key]
        public int SiparisID { get; set; }

        [Column("MusteriID")]
        public int MusteriID { get; set; }

        [Column("SiparisTarihi")]
        public DateTime SiparisTarihi { get; set; }

        [Column("ToplamTutar")]
        public decimal ToplamTutar { get; set; }

        [Column("TeslimatAdresID")]
        public int TeslimatAdresID { get; set; }

        [Column("FaturaAdresID")]
        public int FaturaAdresID { get; set; }

        [Column("OdemeYontemi")]
        public string OdemeYontemi { get; set; }

        [Column("SiparisDurumu")]
        public string SiparisDurumu { get; set; }

        [ForeignKey("MusteriID")]
        public Musteri Musteri { get; set; }

        [ForeignKey("TeslimatAdresID")]
        public Adres TeslimatAdresi { get; set; }

        [ForeignKey("FaturaAdresID")]
        public Adres FaturaAdresi { get; set; }
    }
}
