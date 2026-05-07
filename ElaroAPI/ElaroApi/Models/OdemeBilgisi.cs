using System;
using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;

namespace ElaroApi.Models
{
    [Table("OdemeBilgisi2")]
    public class OdemeBilgisi
    {
        [Key]
        public int OdemeID { get; set; }

        [Column("MusteriID")]
        public int MusteriID { get; set; }

        [Column("KartAdi")]
        public string KartAdi { get; set; }

        [Column("KartNumarasi")]
        public string KartNumarasi { get; set; }

        [Column("SonKullanmaTarihi")]
        public string SonKullanmaTarihi { get; set; }

        [Column("CVV")]
        public string CVV { get; set; }
    }
}
