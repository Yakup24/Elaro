<?php
session_start();
require_once '../db.php';



if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: siparisler.php");
    exit;
}

$siparisID = (int) $_GET['id'];

$stmt = $pdo->prepare("SELECT U.Ad AS UrunAd, SU.Adet, SU.Fiyat
                       FROM SiparisUrun SU
                       JOIN Urun U ON SU.UrunID = U.UrunID
                       WHERE SU.SiparisID = ?");
$stmt->execute([$siparisID]);
$urunler = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Sipariş Detayı</title>
</head>
<body>
<h2>Sipariş Detayı</h2>
<table border="1" cellpadding="10">
    <tr>
        <th>Ürün</th>
        <th>Adet</th>
        <th>Birim Fiyat</th>
        <th>Toplam</th>
    </tr>
    <?php foreach ($urunler as $urun): ?>
        <tr>
            <td><?= htmlspecialchars($urun['UrunAd']) ?></td>
            <td><?= $urun['Adet'] ?></td>
            <td><?= $urun['Fiyat'] ?> ₺</td>
            <td><?= $urun['Adet'] * $urun['Fiyat'] ?> ₺</td>
        </tr>
    <?php endforeach; ?>
</table>
<br>
<a href="siparisler.php">← Tüm siparişlere dön</a>
</body>
</html>
