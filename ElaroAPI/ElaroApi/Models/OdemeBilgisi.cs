using System;
using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;
using System.Text.Json.Serialization;

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
        [JsonIgnore]
        public string KartNumarasi { get; set; }

        [Column("SonKullanmaTarihi")]
        public string SonKullanmaTarihi { get; set; }

        [Column("CVV")]
        [JsonIgnore]
        public string CVV { get; set; }
    }
}
