<?php
session_start();
include("db.php");

// Ürün silme
if (isset($_GET['sil'])) {
    $silID = $_GET['sil'];
    unset($_SESSION['sepet'][$silID]);
    header("Location: sepet.php");
    exit;
}

// Sepeti temizleme
if (isset($_GET['temizle'])) {
    unset($_SESSION['sepet']);
    header("Location: sepet.php");
    exit;
}

// Sepeti güncelleme
if (isset($_POST['guncelle']) && isset($_POST['adet'])) {
    foreach ($_POST['adet'] as $id => $adet) {
        $_SESSION['sepet'][$id]['adet'] = max(1, (int)$adet);
    }
    header("Location: sepet.php");
    exit;
}

// Giriş kontrolü ve MusteriID tespiti
$username = $_SESSION['username'] ?? null;

if (!$username) {
    echo "Giriş yapılmamış.";
    exit;
}

// Ad ve soyadı parçala
$adSoyad = explode(' ', $username);
$ad = $adSoyad[0] ?? '';
$soyad = $adSoyad[1] ?? '';

// Veritabanından MusteriID'yi al
$sorgu = $baglanti->prepare("SELECT MusteriID FROM Musteri2 WHERE Ad = ? AND Soyad = ?");
$sorgu->execute([$ad, $soyad]);
$musteri = $sorgu->fetch(PDO::FETCH_ASSOC);

if (!$musteri) {
    echo "Kullanıcı bulunamadı.";
    exit;
}

$musteriID = $musteri["MusteriID"];
$_SESSION["MusteriID"] = $musteriID; // artık adres kaydında kullanılabilir

// Adres kaydetme ve yönlendirme
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['formtipi']) && $_POST['formtipi'] === 'adres') {
    $adres1 = $_POST['AdresSatiri1'] ?? '';
    $adres2 = $_POST['AdresSatiri2'] ?? '';
    $il = $_POST['Il'] ?? '';
    $ilce = $_POST['Ilce'] ?? '';
    $posta = $_POST['PostaKodu'] ?? '';
    $acikadres = $_POST['AcikAdres'] ?? '';

    $adresSorgu = $baglanti->prepare("INSERT INTO Adres2 (MusteriID, AdresSatiri1, AdresSatiri2, Il, Ilce, PostaKodu, AcikAdres) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $adresSorgu->execute([$musteriID, $adres1, $adres2, $il, $ilce, $posta, $acikadres]);

    $_SESSION['AdresID'] = $baglanti->lastInsertId();

    header("Location: odeme.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Alışveriş Sepetiniz</title>
    <link rel="stylesheet" href="sepet.css">
</head>
<body>
<h1>Alışveriş Sepetiniz</h1>

<div class="sepet">
    <form method="post" class="urun-listesi">
        <?php
        $toplam = 0;
        if (!empty($_SESSION['sepet'])):
            foreach ($_SESSION['sepet'] as $id => $urun):
                $urun_toplam = $urun['fiyat'] * $urun['adet'];
                $toplam += $urun_toplam;
        ?>
        <div class="urun">
            <img src="<?= $urun['resim'] ?>" alt="">
            <div style="flex-grow:1">
                <div class="urun-ad"><?= $urun['ad'] ?></div>
                <div><?= number_format($urun['fiyat'], 2) ?> TL</div>
            </div>
            <input type="number" name="adet[<?= $id ?>]" value="<?= $urun['adet'] ?>" class="adet-input" min="1">
            <div style="margin-left: 20px;"><?= number_format($urun_toplam, 2) ?> TL</div>
            <a href="?sil=<?= $id ?>" class="btn-sil">🗑</a>
        </div>
        <?php
            endforeach;
        else:
        ?>
        <p style="text-align:center;">Sepetinizde ürün bulunmamaktadır.</p>
        <?php endif; ?>
        <div class="alt-bar">
            <a href="index.php">← Alışverişe Devam Et</a>
            <a href="?temizle=1" class="btn-temizle">❌ Sepeti Temizle</a>
            <button type="submit" name="guncelle" class="btn btn-guncelle">🔄 Sepeti Güncelle</button>
        </div>
    </form>

    <div class="ozet">
        <h3>Sipariş Özeti</h3>
        <p>Ara Toplam: <?= number_format($toplam, 2) ?> TL</p>
        <p>Kargo: Hesaplanacak</p>
        <hr>
        <p><strong>Genel Toplam:</strong> <?= number_format($toplam, 2) ?> TL</p>
        <br>

        <!-- ADRES FORMU -->
        <div class="adres-formu">
            <form method="post" action="" class="odeme-form">
                <input type="hidden" name="formtipi" value="adres">

                <h4>Adres Bilgileri</h4>

                <label>Adres Satırı 1</label>
                <input type="text" name="AdresSatiri1" required>

                <label>Adres Satırı 2</label>
                <input type="text" name="AdresSatiri2">

                <label>İl</label>
                <input type="text" name="Il" required>

                <label>İlçe</label>
                <input type="text" name="Ilce" required>

                <label>Posta Kodu</label>
                <input type="text" name="PostaKodu" required>

                <label>Açık Adres</label>
                <textarea name="AcikAdres" rows="3" required></textarea>

                <button type="submit" class="btn btn-odeme">➡️ Ödemeye Geç</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
