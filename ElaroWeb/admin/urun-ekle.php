<?php
session_start();


include("db.php");

$mesaj = "";

// Kategorileri çek
$kategoriler = $baglanti->query("SELECT * FROM Kategori2")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $ad = $_POST["ad"];
    $aciklama = $_POST["aciklama"];
    $fiyat = $_POST["fiyat"];
    $stok = $_POST["stok"];
    $kategori = isset($_POST["kategori"]) ? (int)$_POST["kategori"] : 0;
    $marka = $_POST["marka"];
    $renk = $_POST["renk"];
    $gorsel = $_POST["gorsel"];

    if ($kategori <= 0) {
        $mesaj = "<div class='alert alert-danger'>Lütfen geçerli bir kategori seçiniz.</div>";
    } else {
        try {
            $sql = "INSERT INTO [Urun2] (Adi, Aciklama, Fiyat, StokAdedi, KategoriID, Marka, Renk, GorselURL)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $baglanti->prepare($sql);
            $stmt->execute([$ad, $aciklama, $fiyat, $stok, $kategori, $marka, $renk, $gorsel]);

            $mesaj = "<div class='alert alert-success'>Ürün başarıyla eklendi.</div>";
        } catch (PDOException $e) {
            $mesaj = "<div class='alert alert-danger'>Hata: " . $e->getMessage() . "</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Ürün Ekle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h2 class="mb-4">Yeni Ürün Ekle</h2>

    <?= $mesaj ?>

    <form method="post" class="bg-white p-4 rounded shadow-sm">
        <div class="mb-3">
            <label>Ürün Adı</label>
            <input name="ad" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Açıklama</label>
            <textarea name="aciklama" class="form-control" required></textarea>
        </div>

        <div class="mb-3">
            <label>Fiyat (₺)</label>
            <input name="fiyat" type="number" step="0.01" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Stok Adedi</label>
            <input name="stok" type="number" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Kategori Seç</label>
            <select name="kategori" class="form-control" required>
                <option value="" disabled selected>Kategori seçiniz</option>
                <?php foreach ($kategoriler as $kat): ?>
                    <option value="<?= $kat['KategoriID'] ?>"><?= htmlspecialchars($kat['Adi']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label>Marka</label>
            <input name="marka" class="form-control">
        </div>

        <div class="mb-3">
            <label>Renk</label>
            <input name="renk" class="form-control">
        </div>

        <div class="mb-3">
            <label>Görsel URL</label>
            <input name="gorsel" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Ürünü Ekle</button>
        <a href="panel.php" class="btn btn-secondary">Panele Dön</a>
    </form>
</div>

</body>
</html>
