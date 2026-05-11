<?php
require_once __DIR__ . '/db.php';

function urunleriGetir($baglanti, $kategoriID) {
    $stmt = $baglanti->prepare("SELECT * FROM dbo.Urun2 WHERE KategoriID = ?");
    $stmt->execute([$kategoriID]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// 4: Kadın Aksesuarları, 11: Kız Çocuk Aksesuarları, 14: Erkek Çocuk Aksesuarları
$kadin_aksesuar = urunleriGetir($baglanti, 4);
$kiz_cocuk_aksesuar = urunleriGetir($baglanti, 11);
$erkek_cocuk_aksesuar = urunleriGetir($baglanti, 14);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title>Elaro | Aksesuar Kategorisi</title>
  <link rel="stylesheet" href="E-Ticaret.css">
</head>
<body>

<?php include("partials/header.php"); ?>
<?php include("partials/menu.php"); ?>

<section class="urunler-bolumu">

  <h2>Kadın Aksesuarları</h2>
  <div class="urunler-grid">
    <?php foreach ($kadin_aksesuar as $urun): ?>
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

  <h2>Kız Çocuk Aksesuarları</h2>
  <div class="urunler-grid">
    <?php foreach ($kiz_cocuk_aksesuar as $urun): ?>
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
    <?php foreach ($erkek_cocuk_aksesuar as $urun): ?>
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
