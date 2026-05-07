<?php
include("db.php");
$mesaj = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mevcut = $_POST['mevcut'];
    $yeni = $_POST['yeni'];

    if ($mevcut === "1234") {
        // Gerçek projede hash kullanılmalı
        // örneğin password_verify($mevcut, $hash)
        $mesaj = "Parola başarıyla güncellendi (Demo)!";
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
