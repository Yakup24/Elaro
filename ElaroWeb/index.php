<?php
session_start();
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Elaro | Moda ve Alışverişin Merkezi</title>
  <link rel="stylesheet" href="E-Ticaret.css">
  <script src="mod-degistirici.js" defer></script>
</head>
<body>
<?php include("partials/header.php"); ?>
<?php include("partials/menu.php"); ?>

<br>

<section class="anaslider-hero">
  <div class="anaslider-wrapper">
    <div class="anaslider-slide"><img src="https://i.hizliresim.com/d244odn.png" /></div>
    <div class="anaslider-slide"><img src="https://i.hizliresim.com/q8w58s7.png" /></div>
    <div class="anaslider-slide"><img src="https://i.hizliresim.com/sgw0oxd.png" /></div>
    <div class="anaslider-slide"><img src="https://i.hizliresim.com/o9kk4ur.png" /></div>
    <div class="anaslider-slide"><img src="https://i.hizliresim.com/t3eg3jy.png" /></div>
  </div>
</section>

<section class="kategori-alani">
  <div class="kategori-kart">
    <h3>Kadın</h3>
    <a href="kadin.php">Ürünleri Gör</a>
  </div>
  <div class="kategori-kart">
    <h3>Erkek</h3>
    <a href="erkek.php">Ürünleri Gör</a>
  </div>
  <div class="kategori-kart">
    <h3>Çocuk</h3>
    <a href="cocuk.php">Ürünleri Gör</a>
  </div>
  <div class="kategori-kart">
    <h3>Aksesuar</h3>
    <a href="aksesuarlar.php">Ürünleri Gör</a>
  </div>
</section>

<section class="urunler-bolumu">
  <h2>Öne Çıkan Ürünler</h2>
  <div class="urunler-grid">
    <div class="urun-karti">
      <img src="https://i.hizliresim.com/55xccl1.jpg" alt="Elbise">
      <h4>Şık Yazlık Elbise</h4>
      <p>499 TL</p>
      <button class="sepete-ekle" data-isim="Şık Yazlık Elbise" data-fiyat="499" data-resim="https://i.hizliresim.com/55xccl1.jpg">Sepete Ekle</button>
    </div>
    <div class="urun-karti">
      <img src="https://i.hizliresim.com/jck8u9h.jpg" alt="Gömlek">
      <h4>Klasik Erkek Gömlek</h4>
      <p>299 TL</p>
      <button class="sepete-ekle" data-isim="Klasik Erkek Gömlek" data-fiyat="299" data-resim="https://i.hizliresim.com/jck8u9h.jpg">Sepete Ekle</button>
    </div>
    <div class="urun-karti">
      <img src="https://i.hizliresim.com/by96lho.jpg" alt="Çocuk Tişört">
      <h4>Renkli Çocuk Tişörtü</h4>
      <p>149 TL</p>
      <button class="sepete-ekle" data-isim="Renkli Çocuk Tişörtü" data-fiyat="149" data-resim="https://i.hizliresim.com/by96lho.jpg">Sepete Ekle</button>
    </div>
  </div>
</section>

<script src="sepet.js"></script>
<?php include("partials/footer.php"); ?>
</body>
</html>
