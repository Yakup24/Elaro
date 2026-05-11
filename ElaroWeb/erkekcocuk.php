<?php
require_once __DIR__ . '/db.php';

function urunleriGetir($baglanti, $kategoriID) {
    $stmt = $baglanti->prepare("SELECT * FROM dbo.Urun2 WHERE KategoriID = ?");
    $stmt->execute([$kategoriID]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$ayakkabilar = urunleriGetir($baglanti, 13);
$kiyafetler  = urunleriGetir($baglanti, 12);
$aksesuarlar= urunleriGetir($baglanti, 14);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title>Elaro | Erkek Çocuk Kategorisi</title>
  <link rel="stylesheet" href="E-Ticaret.css">
</head>
<body>

<?php include("partials/header.php"); ?>
<?php include("partials/menu.php"); ?>
<section class="urunler-bolumu">

<h2>Erkek Çocuk Kıyafetleri</h2>
  <div class="urunler-grid">
    <?php foreach ($kiyafetler as $urun): ?>
      <div class="urun-karti">
        <img src="<?= $urun['GorselURL'] ?>" alt="<?= $urun['Adi'] ?>">
        <h4><?= $urun['Adi'] ?></h4>
        <p><?= $urun['Fiyat'] ?> TL</p>

        <a href="urun-detay.php?id=<?= $urun['UrunID'] ?>" class="btn-detay"
           style="display:inline-block; background:#007bff; color:white; padding:8px 14px; border-radius:6px; text-decoration:none; margin-bottom:8px;">
          Ürün Detay
        </a>

        <button class="sepete-ekle"
                data-isim="<?= $urun['Adi'] ?>"
                data-fiyat="<?= $urun['Fiyat'] ?>"
                data-resim="<?= $urun['GorselURL'] ?>">
          Sepete Ekle
        </button>
      </div>
    <?php endforeach; ?>
  </div>

  <h2>Erkek Çocuk Ayakkabıları</h2>
  <div class="urunler-grid">
    <?php foreach ($ayakkabilar as $urun): ?>
      <div class="urun-karti">
        <img src="<?= $urun['GorselURL'] ?>" alt="<?= $urun['Adi'] ?>">
        <h4><?= $urun['Adi'] ?></h4>
        <p><?= $urun['Fiyat'] ?> TL</p>

        <a href="urun-detay.php?id=<?= $urun['UrunID'] ?>" class="btn-detay"
           style="display:inline-block; background:#007bff; color:white; padding:8px 14px; border-radius:6px; text-decoration:none; margin-bottom:8px;">
          Ürün Detay
        </a>

        <button class="sepete-ekle"
                data-isim="<?= $urun['Adi'] ?>"
                data-fiyat="<?= $urun['Fiyat'] ?>"
                data-resim="<?= $urun['GorselURL'] ?>">
          Sepete Ekle
        </button>
      </div>
    <?php endforeach; ?>
  </div>

  <h2>Erkek Çocuk Aksesuarları</h2>
  <div class="urunler-grid">
    <?php foreach ($aksesuarlar as $urun): ?>
      <div class="urun-karti">
        <img src="<?= $urun['GorselURL'] ?>" alt="<?= $urun['Adi'] ?>">
        <h4><?= $urun['Adi'] ?></h4>
        <p><?= $urun['Fiyat'] ?> TL</p>

        <a href="urun-detay.php?id=<?= $urun['UrunID'] ?>" class="btn-detay"
           style="display:inline-block; background:#007bff; color:white; padding:8px 14px; border-radius:6px; text-decoration:none; margin-bottom:8px;">
          Ürün Detay
        </a>

        <button class="sepete-ekle"
                data-isim="<?= $urun['Adi'] ?>"
                data-fiyat="<?= $urun['Fiyat'] ?>"
                data-resim="<?= $urun['GorselURL'] ?>">
          Sepete Ekle
        </button>
      </div>
    <?php endforeach; ?>
  </div>
</section>


<?php include("partials/footer.php"); ?>
<script src="sepet.js"></script>
</body>
</html>
