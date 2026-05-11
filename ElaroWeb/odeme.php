<?php
session_start();
include("db.php");

if (!$baglanti) die("Veritabanı bağlantı hatası.");

if (empty($_SESSION['sepet'])) {
    header("Location: index.php");
    exit;
}

$mesaj = "";
$toplam = 0;
foreach ($_SESSION['sepet'] as $urun) {
    $toplam += $urun['fiyat'] * $urun['adet'];
}

// Giriş yapan kullanıcıyı al
$username = $_SESSION['username'] ?? null;
if (!$username) {
    $mesaj = "<p style='color:red;'>Kullanıcı oturumu bulunamadı.</p>";
}

$adSoyad = explode(' ', $username);
$ad = $adSoyad[0] ?? '';
$soyad = $adSoyad[1] ?? '';

$sorgu = $baglanti->prepare("SELECT MusteriID FROM Musteri2 WHERE Ad = ? AND Soyad = ?");
$sorgu->execute([$ad, $soyad]);
$musteri = $sorgu->fetch(PDO::FETCH_ASSOC);
$musteriID = $musteri["MusteriID"] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!$musteriID) {
        $mesaj = "<p style='color:red;'>Müşteri oturumu yok.</p>";
    } else {
        // Payment data is not stored in full; keep only a masked card value.
        $kart = $_POST['kart'] ?? '';
        $son = $_POST['sonkullanma'] ?? '';
        $odemeYontemi = $_POST['yontem'] ?? 'Kredi Kartı';
        $kartRakamlar = preg_replace('/\D/', '', $kart);
        $kartSonDort = substr($kartRakamlar, -4);
        $maskeliKart = $kartSonDort ? '************' . $kartSonDort : '';

        try {
            $stmtOdeme = $baglanti->prepare("INSERT INTO [OdemeBilgisi2] 
                ([MusteriID], [KartNumarasi], [SonKullanmaTarihi], [CVV], [OdemeYontemi])
                VALUES (?, ?, ?, ?, ?)");
            $stmtOdeme->execute([$musteriID, $maskeliKart, $son, '', $odemeYontemi]);

            // 2. Sipariş kaydet
            $tarih = date('Y-m-d H:i:s');
            $stmtSiparis = $baglanti->prepare("INSERT INTO [Siparis2] 
                ([MusteriID], [SiparisTarihi], [ToplamTutar], [SiparisDurumu]) 
                VALUES (?, ?, ?, ?)");
            $stmtSiparis->execute([$musteriID, $tarih, $toplam, 'Hazırlanıyor']);

            // Sepeti temizle
            unset($_SESSION['sepet']);

            $mesaj = "<p style='color:green;'>✔️ Sipariş başarıyla tamamlandı.</p>";

        } catch (PDOException $e) {
            error_log("Checkout failed: " . $e->getMessage());
            $mesaj = "<p style='color:red;'>Sipariş tamamlanırken bir hata oluştu.</p>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Ödeme</title>
    <link rel="stylesheet" href="sepet.css">
    <style>
        .odeme-form input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
        }
        .odeme-form {
            max-width: 400px;
            margin: auto;
        }
    </style>
</head>
<body>
<center>
<h1>💳 Ödeme Sayfası</h1>
<div class="ozet">
    <h3>Genel Toplam: <?= number_format($toplam, 2) ?> TL</h3>
    <p>Kargo: Ücretsiz</p>
    <hr>
    <?= $mesaj ?>
    <form method="post" class="odeme-form">
        <label>Kart Numarası:</label>
        <input type="text" name="kart" placeholder="XXXX XXXX XXXX XXXX" required>

        <label>Son Kullanma Tarihi:</label>
        <input type="text" name="sonkullanma" placeholder="AA/YY" required>

        <input type="hidden" name="yontem" value="Kredi Kartı">

        <button type="submit" class="btn btn-odeme">✔️ Ödemeyi Tamamla</button>
    </form>
</div>
</center>
</body>
</html>
