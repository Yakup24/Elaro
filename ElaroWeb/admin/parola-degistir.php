<?php
require_once __DIR__ . '/auth.php';
include("db.php");
$mesaj = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mevcut = $_POST['mevcut'] ?? '';
    $yeni = $_POST['yeni'] ?? '';

    $stmt = $baglanti->prepare("SELECT Sifre FROM dbo.Musteri2 WHERE MusteriID = ?");
    $stmt->execute([$_SESSION['admin_id']]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);
    $storedPassword = $admin['Sifre'] ?? '';

    $passwordValid = $admin && (password_verify($mevcut, $storedPassword) || hash_equals($storedPassword, $mevcut));

    if ($passwordValid) {
        $newHash = password_hash($yeni, PASSWORD_DEFAULT);
        $update = $baglanti->prepare("UPDATE dbo.Musteri2 SET Sifre = ? WHERE MusteriID = ?");
        $update->execute([$newHash, $_SESSION['admin_id']]);
        $mesaj = "Parola başarıyla güncellendi.";
    } else {
        $mesaj = "Mevcut parola yanlış.";
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Parola Değiştir</title>
</head>
<body>
<h2>Parola Değiştir</h2>
<form method="post">
    <label>Mevcut Parola:</label><br>
    <input name="mevcut" type="password" required><br><br>
    <label>Yeni Parola:</label><br>
    <input name="yeni" type="password" required><br><br>
    <button type="submit">Güncelle</button>
</form>
<p style="color:green;">
    <?= $mesaj ?>
</p>
</body>
</html>
