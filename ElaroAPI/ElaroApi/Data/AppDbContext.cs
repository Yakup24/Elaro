using Microsoft.EntityFrameworkCore;
using ElaroApi.Models;

namespace ElaroApi.Data
{
    public class AppDbContext : DbContext
    {
        public AppDbContext(DbContextOptions<AppDbContext> options) : base(options) { }

        public DbSet<Musteri> Musteriler { get; set; }
        public DbSet<Urun> Urunler { get; set; }
        public DbSet<Kategori> Kategoriler { get; set; }
        public DbSet<Siparis> Siparisler { get; set; }
        public DbSet<Adres> Adresler { get; set; }
        public DbSet<Yorum> Yorumlar { get; set; }
        public DbSet<UrunBeden> UrunBedenler { get; set; }
        public DbSet<OdemeBilgisi> OdemeBilgileri { get; set; }
        public DbSet<Yonetici> Yoneticiler { get; set; }

        protected override void OnModelCreating(ModelBuilder modelBuilder)
        {
            // Veritabanı tablolarını doğru eşleştir
            modelBuilder.Entity<Urun>().ToTable("Urun2");

            // Diğer modellerin de tablo isimleri farklıysa aynı şekilde eklenebilir:
            // modelBuilder.Entity<Musteri>().ToTable("Musteri2");
            // modelBuilder.Entity<Adres>().ToTable("Adres2");
        }
    }
}
