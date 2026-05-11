<?php
require_once __DIR__ . '/auth.php';
include("db.php");

// Sayılar
$urunSayisi = $baglanti->query("SELECT COUNT(*) FROM Urun2")->fetchColumn();
$siparisSayisi = $baglanti->query("SELECT COUNT(*) FROM Siparis2")->fetchColumn();
$kullaniciSayisi = $baglanti->query("SELECT COUNT(*) FROM Musteri2")->fetchColumn();

// Son ürünler
$urunler = $baglanti->query("SELECT * FROM Urun2 ORDER BY UrunID DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title>Yönetici Paneli</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
  <h1 class="mb-4">Hoş Geldiniz, <?= $_SESSION["admin_kadi"] ?? 'Admin' ?></h1>

  <div class="row g-3">
    <div class="col-md-4">
      <div class="card text-bg-primary">
        <div class="card-body">
          <h5 class="card-title">Toplam Urun2</h5>
          <p class="card-text fs-3"><?= $urunSayisi ?></p>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card text-bg-success">
        <div class="card-body">
          <h5 class="card-title">Toplam Siparis2</h5>
          <p class="card-text fs-3"><?= $siparisSayisi ?></p>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card text-bg-warning">
        <div class="card-body">
          <h5 class="card-title">Toplam Kullanıcı</h5>
          <p class="card-text fs-3"><?= $kullaniciSayisi ?></p>
        </div>
      </div>
    </div>
  </div>

  <h3 class="mt-5">Tum Ürünler</h3>
  <table class="table table-bordered mt-2">
    <thead>
      <tr>
        <th>Urun2 Adı</th>
        <th>Fiyat</th>
        <th>Sil</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($urunler as $urun): ?>
        <tr>
          <td><?= htmlspecialchars($urun['Adi']) ?></td>
          <td><?= number_format($urun['Fiyat'], 2) ?> TL</td>
          <td><a href="urun-sil.php?id=<?= $urun['UrunID'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Ürünü silmek istediğinizden emin misiniz?')">Sil</a></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <div class="mt-4">
    <a href="urun-ekle.php" class="btn btn-outline-primary">Urun2 Ekle</a>
    <a href="siparisler.php" class="btn btn-outline-success">Siparişler</a>
    <a href="parola-degistir.php" class="btn btn-outline-warning">Parola Değiştir</a>
    <a href="cikis.php" class="btn btn-outline-danger">Çıkış Yap</a>
  </div>
</div>
</body>
</html>
