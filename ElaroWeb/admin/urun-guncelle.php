<?php
require_once __DIR__ . '/auth.php';
include("db.php");



if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: urun-listele.php");
    exit;
}

$urunID = (int) $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ad = $_POST['ad'];
    $aciklama = $_POST['aciklama'];
    $fiyat = $_POST['fiyat'];
    $stok = $_POST['stok'];
    $kategori = $_POST['kategori'];
    $marka = $_POST['marka'];
    $renk = $_POST['renk'];
    $gorsel = $_POST['gorsel'];

    $sql = "UPDATE Urun SET Ad=?, Aciklama=?, Fiyat=?, StokAdedi=?, KategoriID=?, Marka=?, Renk=?, GorselURL=? WHERE UrunID=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$ad, $aciklama, $fiyat, $stok, $kategori, $marka, $renk, $gorsel, $urunID]);

    header("Location: urun-listele.php");
    exit;
} else {
    $stmt = $pdo->prepare("SELECT * FROM Urun WHERE UrunID = ?");
    $stmt->execute([$urunID]);
    $urun = $stmt->fetch();
    if (!$urun) {
        echo "Ürün bulunamadı.";
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Ürün Güncelle</title>
</head>
<body>
<h2>Ürün Güncelle</h2>
<form method="post">
    <input name="ad" value="<?= htmlspecialchars($urun['Ad']) ?>" required><br><br>
    <textarea name="aciklama" required><?= htmlspecialchars($urun['Aciklama']) ?></textarea><br><br>
    <input name="fiyat" type="number" step="0.01" value="<?= $urun['Fiyat'] ?>" required><br><br>
    <input name="stok" type="number" value="<?= $urun['StokAdedi'] ?>" required><br><br>
    <input name="kategori" type="number" value="<?= $urun['KategoriID'] ?>" required><br><br>
    <input name="marka" value="<?= htmlspecialchars($urun['Marka']) ?>"><br><br>
    <input name="renk" value="<?= htmlspecialchars($urun['Renk']) ?>"><br><br>
    <input name="gorsel" value="<?= htmlspecialchars($urun['GorselURL']) ?>"><br><br>
    <button type="submit">Güncelle</button>
</form>
</body>
</html>
