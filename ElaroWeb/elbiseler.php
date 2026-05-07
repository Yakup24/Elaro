<?php
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

// Elbiseler için kategori ID
$kategori_id = 1;

// Kategori adı
$katSorgu = $baglanti->prepare("SELECT Adi FROM dbo.Kategori2 WHERE KategoriID = ?");
$katSorgu->execute([$kategori_id]);
$kat = $katSorgu->fetch(PDO::FETCH_ASSOC);

// Ürünleri çek
$urunSorgu = $baglanti->prepare("SELECT * FROM dbo.Urun2 WHERE KategoriID = ?");
$urunSorgu->execute([$kategori_id]);
$urunler = $urunSorgu->fetchAll(PDO::FETCH_ASSOC);
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
    <h2><?= $kat ? $kat['Adi'] : 'Kategori Bulunamadı' ?></h2>
    <div class="urunler-grid">
        <?php foreach ($urunler as $urun): ?>
        <div class="urun-karti">
            <img src="<?= htmlspecialchars($urun['GorselURL']) ?>" alt="<?= htmlspecialchars($urun['Adi']) ?>">
            <h4><?= htmlspecialchars($urun['Adi']) ?></h4>
            <p><?= number_format($urun['Fiyat'], 2) ?> TL</p>

            <!-- Ürün Detay Butonu -->
            <a href="urun-detay.php?id=<?= $urun['UrunID'] ?>" class="btn-detay"
               style="display:inline-block; background:#007bff; color:white; padding:8px 14px; border-radius:6px; text-decoration:none; margin-bottom:8px;">
               Ürün Detay
            </a>

            <!-- Sepete Ekle Butonu -->
            <button class="sepete-ekle"
                    data-isim="<?= htmlspecialchars($urun['Adi']) ?>"
                    data-fiyat="<?= $urun['Fiyat'] ?>"
                    data-resim="<?= htmlspecialchars($urun['GorselURL']) ?>">
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
