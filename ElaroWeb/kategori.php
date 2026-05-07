<?php
$host = 'elaro.database.windows.net';
$dbname = 'elaro';
$username = 'elaro';
$password = 'M3sl3ki.proje';

try {
    $baglanti = new PDO("sqlsrv:Server=$host;Database=$dbname", $username, $password);
    $baglanti->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Bağlantı hatası: " . $e->getMessage());
}

// Kategori ID al
$kategori_id = $_GET['id'] ?? 0;

// Kategori adını çek
$katSorgu = $baglanti->prepare("SELECT Ad FROM Kategori WHERE KategoriID = ?");
$katSorgu->execute([$kategori_id]);
$kat = $katSorgu->fetch(PDO::FETCH_ASSOC);

// Ürünleri çek
$urunSorgu = $baglanti->prepare("SELECT * FROM Urun WHERE KategoriID = ?");
$urunSorgu->execute([$kategori_id]);
$urunler = $urunSorgu->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title><?= $kat ? $kat['Ad'] : 'Kategori' ?></title>
    <link rel="stylesheet" href="E-Ticaret.css">
</head>
<body>

<section class="urunler-bolumu">
    <h2><?= $kat ? $kat['Ad'] : 'Kategori Bulunamadı' ?></h2>
    <div class="urunler-grid">
        <?php foreach ($urunler as $urun): ?>
        <div class="urun-karti">
            <img src="<?= $urun['GörselURL'] ?>" alt="<?= $urun['Ad'] ?>">
            <h4><?= $urun['Ad'] ?></h4>
            <p><?= $urun['Fiyat'] ?> TL</p>
            <button class="sepete-ekle" data-isim="<?= $urun['Ad'] ?>" data-fiyat="<?= $urun['Fiyat'] ?>">Sepete Ekle</button>
        </div>
        <?php endforeach; ?>
    </div>
</section>

</body>
</html>
