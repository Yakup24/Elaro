USE Elaro;
GO

SET IDENTITY_INSERT dbo.Kategori ON;

MERGE dbo.Kategori AS target
USING (VALUES
    (1, N'Erkek', N'Erkek giyim urunleri', NULL),
    (2, N'Kadin', N'Kadin giyim urunleri', NULL),
    (3, N'Aksesuar', N'Tamamlayici aksesuarlar', NULL)
) AS source (KategoriID, Ad, Aciklama, UstKategoriID)
ON target.KategoriID = source.KategoriID
WHEN NOT MATCHED THEN
    INSERT (KategoriID, Ad, [Açıklama], [ÜstKategoriID])
    VALUES (source.KategoriID, source.Ad, source.Aciklama, source.UstKategoriID);

SET IDENTITY_INSERT dbo.Kategori OFF;
GO

MERGE dbo.Urun2 AS target
USING (VALUES
    (N'Siyah Hoodie', N'Gunluk kullanim icin rahat hoodie', 899.90, 25, 1, N'Elaro', N'Siyah', N'/images/siyah-hoodie.jpg'),
    (N'Beyaz Sneaker', N'Gunluk kullanim icin sneaker', 1499.00, 12, 2, N'Elaro', N'Beyaz', N'/images/beyaz-sneaker.jpg'),
    (N'Deri Cuzdan', N'Minimal kart ve nakit cuzdan', 349.90, 40, 3, N'Elaro', N'Kahverengi', N'/images/deri-cuzdan.jpg')
) AS source (Adi, Aciklama, Fiyat, StokAdedi, KategoriID, Marka, Renk, GorselURL)
ON target.Adi = source.Adi
WHEN NOT MATCHED THEN
    INSERT (Adi, Aciklama, Fiyat, StokAdedi, KategoriID, Marka, Renk, GorselURL)
    VALUES (source.Adi, source.Aciklama, source.Fiyat, source.StokAdedi, source.KategoriID, source.Marka, source.Renk, source.GorselURL);
GO

-- Admin/customer users should be created with BCrypt hashes through the app flow
-- or a private local script. Do not commit real passwords or reusable demo secrets.
