
<?php
session_start();

$host = 'elaro.database.windows.net';
$dbname = 'elaro';
$username = 'elaroadmin';
$password = 'M3sl3ki.proje';

try {
    $baglanti = new PDO("sqlsrv:Server=$host;Database=$dbname", $username, $password);
    $baglanti->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Bağlantı hatası: " . $e->getMessage());
}

// Fonksiyonla çek
function urunleriGetir($baglanti, $kategori_id) {
    $stmt = $baglanti->prepare("SELECT * FROM dbo.Urun2 WHERE KategoriID = ?");
    $stmt->execute([$kategori_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Kategorilere göre ürünleri çek
$kiz_cocuk_kiyafet = urunleriGetir($baglanti, 9);
$kiz_cocuk_ayakkabi = urunleriGetir($baglanti, 10);
$kiz_cocuk_aksesuar = urunleriGetir($baglanti, 11);

$erkek_cocuk_kiyafet = urunleriGetir($baglanti, 12);
$erkek_cocuk_ayakkabi = urunleriGetir($baglanti, 13);
$erkek_cocuk_aksesuar = urunleriGetir($baglanti, 14);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Elaro | Moda ve Alışverişin Merkezi</title>
  <link rel="stylesheet" href="E-Ticaret.css">
  <script src="mod-degistirici.js" defer></script>
</head>
<body>

<?php include("partials/header.php"); ?>
<?php include("partials/menu.php"); ?>

<section class="urunler-bolumu">
  <h2>Kız Çocuk Kıyafetleri</h2>
  <div class="urunler-grid">
    <?php foreach ($kiz_cocuk_kiyafet as $urun): ?>
      <div class="urun-karti">
        <img src="<?= $urun['GorselURL'] ?>" alt="<?= $urun['Adi'] ?>">
        <h4><?= $urun['Adi'] ?></h4>
        <p><?= $urun['Fiyat'] ?> TL</p>

        <!-- Ürün Detay Butonu -->
        <a href="urun-detay.php?id=<?= $urun['UrunID'] ?>" class="btn-detay"
           style="display:inline-block; background:#007bff; color:white; padding:8px 14px; border-radius:6px; text-decoration:none; margin-bottom:8px;">
          Ürün Detay
        </a>

        <!-- Sepete Ekle Butonu -->
        <button class="sepete-ekle"
                data-isim="<?= $urun['Adi'] ?>"
                data-fiyat="<?= $urun['Fiyat'] ?>"
                data-resim="<?= $urun['GorselURL'] ?>">
          Sepete Ekle
        </button>
      </div>
    <?php endforeach; ?>
  </div>

  <h2>Kız Çocuk Ayakkabıları</h2>
  <div class="urunler-grid">
    <?php foreach ($kiz_cocuk_ayakkabi as $urun): ?>
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

  <h2>Erkek Çocuk Kıyafetleri</h2>
  <div class="urunler-grid">
    <?php foreach ($erkek_cocuk_kiyafet as $urun): ?>
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
    <?php foreach ($erkek_cocuk_ayakkabi as $urun): ?>
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
