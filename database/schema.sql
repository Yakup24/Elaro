IF DB_ID(N'Elaro') IS NULL
BEGIN
    CREATE DATABASE Elaro;
END;
GO

USE Elaro;
GO

IF OBJECT_ID(N'dbo.Musteri2', N'U') IS NULL
BEGIN
    CREATE TABLE dbo.Musteri2 (
        MusteriID INT IDENTITY(1,1) NOT NULL CONSTRAINT PK_Musteri2 PRIMARY KEY,
        Ad NVARCHAR(100) NOT NULL,
        Soyad NVARCHAR(100) NOT NULL,
        Eposta NVARCHAR(200) NOT NULL,
        Telefon NVARCHAR(30) NULL,
        Sifre NVARCHAR(255) NOT NULL,
        KayitTarihi DATETIME2 NULL CONSTRAINT DF_Musteri2_KayitTarihi DEFAULT SYSUTCDATETIME(),
        Cinsiyet NVARCHAR(40) NULL,
        DogumTarihi DATETIME2 NULL,
        CONSTRAINT UQ_Musteri2_Eposta UNIQUE (Eposta)
    );
END;
GO

IF OBJECT_ID(N'dbo.Yonetici2', N'U') IS NULL
BEGIN
    CREATE TABLE dbo.Yonetici2 (
        YoneticiID INT IDENTITY(1,1) NOT NULL CONSTRAINT PK_Yonetici2 PRIMARY KEY,
        KullaniciAdi NVARCHAR(100) NOT NULL,
        Sifre NVARCHAR(255) NOT NULL,
        AdSoyad NVARCHAR(150) NOT NULL,
        Email NVARCHAR(200) NOT NULL,
        CONSTRAINT UQ_Yonetici2_KullaniciAdi UNIQUE (KullaniciAdi),
        CONSTRAINT UQ_Yonetici2_Email UNIQUE (Email)
    );
END;
GO

IF OBJECT_ID(N'dbo.Kategori', N'U') IS NULL
BEGIN
    CREATE TABLE dbo.Kategori (
        KategoriID INT IDENTITY(1,1) NOT NULL CONSTRAINT PK_Kategori PRIMARY KEY,
        Ad NVARCHAR(150) NOT NULL,
        [Açıklama] NVARCHAR(500) NULL,
        [ÜstKategoriID] INT NULL,
        CONSTRAINT FK_Kategori_UstKategori FOREIGN KEY ([ÜstKategoriID]) REFERENCES dbo.Kategori(KategoriID)
    );
END;
GO

IF OBJECT_ID(N'dbo.Urun2', N'U') IS NULL
BEGIN
    CREATE TABLE dbo.Urun2 (
        UrunID INT IDENTITY(1,1) NOT NULL CONSTRAINT PK_Urun2 PRIMARY KEY,
        Adi NVARCHAR(200) NOT NULL,
        Aciklama NVARCHAR(1000) NOT NULL,
        Fiyat DECIMAL(18,2) NOT NULL,
        StokAdedi INT NOT NULL CONSTRAINT DF_Urun2_StokAdedi DEFAULT 0,
        KategoriID INT NOT NULL,
        Marka NVARCHAR(120) NOT NULL,
        Renk NVARCHAR(80) NOT NULL,
        GorselURL NVARCHAR(500) NOT NULL,
        CONSTRAINT FK_Urun2_Kategori FOREIGN KEY (KategoriID) REFERENCES dbo.Kategori(KategoriID),
        CONSTRAINT CK_Urun2_Fiyat CHECK (Fiyat >= 0),
        CONSTRAINT CK_Urun2_Stok CHECK (StokAdedi >= 0)
    );
END;
GO

IF OBJECT_ID(N'dbo.Adres2', N'U') IS NULL
BEGIN
    CREATE TABLE dbo.Adres2 (
        AdresID INT IDENTITY(1,1) NOT NULL CONSTRAINT PK_Adres2 PRIMARY KEY,
        MusteriID INT NOT NULL,
        AdresSatiri1 NVARCHAR(250) NOT NULL,
        AdresSatiri2 NVARCHAR(250) NULL,
        Il NVARCHAR(100) NOT NULL,
        Ilce NVARCHAR(100) NOT NULL,
        PostaKodu NVARCHAR(20) NOT NULL,
        AcikAdres NVARCHAR(500) NOT NULL,
        CONSTRAINT FK_Adres2_Musteri FOREIGN KEY (MusteriID) REFERENCES dbo.Musteri2(MusteriID)
    );
END;
GO

IF OBJECT_ID(N'dbo.Siparis2', N'U') IS NULL
BEGIN
    CREATE TABLE dbo.Siparis2 (
        SiparisID INT IDENTITY(1,1) NOT NULL CONSTRAINT PK_Siparis2 PRIMARY KEY,
        MusteriID INT NOT NULL,
        SiparisTarihi DATETIME2 NOT NULL CONSTRAINT DF_Siparis2_Tarih DEFAULT SYSUTCDATETIME(),
        ToplamTutar DECIMAL(18,2) NOT NULL,
        TeslimatAdresID INT NULL,
        FaturaAdresID INT NULL,
        OdemeYontemi NVARCHAR(80) NULL,
        SiparisDurumu NVARCHAR(80) NOT NULL,
        CONSTRAINT FK_Siparis2_Musteri FOREIGN KEY (MusteriID) REFERENCES dbo.Musteri2(MusteriID),
        CONSTRAINT FK_Siparis2_TeslimatAdres FOREIGN KEY (TeslimatAdresID) REFERENCES dbo.Adres2(AdresID),
        CONSTRAINT FK_Siparis2_FaturaAdres FOREIGN KEY (FaturaAdresID) REFERENCES dbo.Adres2(AdresID),
        CONSTRAINT CK_Siparis2_ToplamTutar CHECK (ToplamTutar >= 0)
    );
END;
GO

IF OBJECT_ID(N'dbo.Yorum2', N'U') IS NULL
BEGIN
    CREATE TABLE dbo.Yorum2 (
        YorumID INT IDENTITY(1,1) NOT NULL CONSTRAINT PK_Yorum2 PRIMARY KEY,
        MusteriID INT NOT NULL,
        UrunID INT NOT NULL,
        Icerik NVARCHAR(1000) NOT NULL,
        Puan INT NOT NULL,
        Tarih DATETIME2 NOT NULL CONSTRAINT DF_Yorum2_Tarih DEFAULT SYSUTCDATETIME(),
        CONSTRAINT FK_Yorum2_Musteri FOREIGN KEY (MusteriID) REFERENCES dbo.Musteri2(MusteriID),
        CONSTRAINT FK_Yorum2_Urun FOREIGN KEY (UrunID) REFERENCES dbo.Urun2(UrunID),
        CONSTRAINT CK_Yorum2_Puan CHECK (Puan BETWEEN 1 AND 5)
    );
END;
GO

IF OBJECT_ID(N'dbo.UrunBeden2', N'U') IS NULL
BEGIN
    CREATE TABLE dbo.UrunBeden2 (
        ID INT IDENTITY(1,1) NOT NULL CONSTRAINT PK_UrunBeden2 PRIMARY KEY,
        UrunID INT NOT NULL,
        Beden NVARCHAR(30) NOT NULL,
        Stok INT NOT NULL CONSTRAINT DF_UrunBeden2_Stok DEFAULT 0,
        CONSTRAINT FK_UrunBeden2_Urun FOREIGN KEY (UrunID) REFERENCES dbo.Urun2(UrunID),
        CONSTRAINT CK_UrunBeden2_Stok CHECK (Stok >= 0)
    );
END;
GO

IF OBJECT_ID(N'dbo.OdemeBilgisi2', N'U') IS NULL
BEGIN
    CREATE TABLE dbo.OdemeBilgisi2 (
        OdemeID INT IDENTITY(1,1) NOT NULL CONSTRAINT PK_OdemeBilgisi2 PRIMARY KEY,
        MusteriID INT NOT NULL,
        KartAdi NVARCHAR(150) NOT NULL,
        KartNumarasi NVARCHAR(32) NOT NULL,
        SonKullanmaTarihi NVARCHAR(10) NOT NULL,
        CVV NVARCHAR(10) NULL,
        OdemeYontemi NVARCHAR(80) NOT NULL CONSTRAINT DF_OdemeBilgisi2_Yontem DEFAULT N'Kredi Karti',
        CONSTRAINT FK_OdemeBilgisi2_Musteri FOREIGN KEY (MusteriID) REFERENCES dbo.Musteri2(MusteriID)
    );
END;
GO
