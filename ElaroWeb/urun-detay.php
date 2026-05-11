<?php
session_start();
include("db.php");

$urunID = $_GET['id'] ?? null;

if (!$urunID) {
    die("Ürün ID belirtilmedi.");
}

$stmt = $baglanti->prepare("SELECT * FROM Urun2 WHERE UrunID = ?");
$stmt->execute([$urunID]);
$urun = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$urun) {
    die("Ürün bulunamadı.");
}
?>


<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($urun['Adi']) ?> | PikPazar</title>
    <link rel="stylesheet" href="E-Ticaret.css">
    <style>
        .btn-sepete-ekle {
            display: inline-block;
            width: 80%;
            background-color: #ffd700;
            color: #000;
            border: none;
            padding: 12px;
            margin: 10px auto;
            font-weight: bold;
            text-align: center;
            border-radius: 25px;
            cursor: pointer;
            text-decoration: none;
            transition: 0.3s;
        }
        .btn-sepete-ekle:hover {
            background-color: #e6be00;
        }
        .urun-detay {
            text-align: center;
            max-width: 600px;
            margin: auto;
            padding: 30px;
        }
        .urun-detay img {
            max-width: 300px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<?php include("partials/header.php"); ?>
<?php include("partials/menu.php"); ?>

<div class="urun-detay">
    <img src="<?= htmlspecialchars($urun['GorselURL']) ?>" alt="<?= htmlspecialchars($urun['Adi']) ?>">
    
    <h2><?= htmlspecialchars($urun['Adi']) ?></h2>
    <p style="margin-top:10px; font-size:17px;"><?= htmlspecialchars($urun['Aciklama']) ?></p>
    <p class="fiyat" style="font-size:20px; color:#d10d0d; margin-top:15px; font-weight:bold;"><?= htmlspecialchars($urun['Fiyat']) ?> TL</p>

    <div class="secenekler">
  <label for="beden">Beden:</label>
  <select id="beden" name="beden">
    <option value="S">S</option>
    <option value="M">M</option>
    <option value="L">L</option>
    <option value="XL">XL</option>
  </select>

  <label for="renk">Renk:</label>
  <select id="renk" name="renk">
    <option value="Beyaz">Beyaz</option>
    <option value="Mavi">Mavi</option>
    <option value="Siyah">Siyah</option>
  </select>
</div>

<div class="yorumlar">
  <h3>Müşteri Yorumları (Ortalama Yıldız: 4.5)</h3>
  <div class="yorum">
    <p><strong>Ahmet:</strong> Çok kaliteli. (5 Yıldız)</p>
  </div>
  <div class="yorum">
    <p><strong>Ayşe:</strong> Biraz dar kesim. (4 Yıldız)</p>
  </div>
</div>

    <a href="sepet.php" 
       class="btn-sepete-ekle sepete-ekle"
       data-isim="<?= htmlspecialchars($urun['Adi']) ?>"
       data-fiyat="<?= htmlspecialchars($urun['Fiyat']) ?>"
       data-resim="<?= htmlspecialchars($urun['GorselURL']) ?>">
        HEMEN AL
    </a>

    <button class="btn-sepete-ekle sepete-ekle"
       data-isim="<?= htmlspecialchars($urun['Adi']) ?>"
       data-fiyat="<?= htmlspecialchars($urun['Fiyat']) ?>"
       data-resim="<?= htmlspecialchars($urun['GorselURL']) ?>">
        Sepete Ekle
    </button>
</div>

<?php include("partials/footer.php"); ?>
<script src="sepet.js"></script>
</body>
</html>
