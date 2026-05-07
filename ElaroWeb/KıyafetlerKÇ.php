<?php
$host = 'elaro.database.windows.net';
$dbname = 'elaro';
$username = 'elaroadmin';
$password = 'M3sl3ki.proje';

try {
    $baglanti = new PDO("sqlsrv:Server=$host;Database=$dbname", $username, $password);
    $baglanti->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Bağlantı hatası: " . $e->getMessage());
}

// Elbiseler için kategori ID
$kategori_id = 9;

// Kategori adı
$katSorgu = $baglanti->prepare("SELECT Adi FROM dbo.Kategori2 WHERE KategoriID = ?");
$katSorgu->execute([$kategori_id]);
$kat = $katSorgu->fetch(PDO::FETCH_ASSOC);

// Ürünleri çek
$urunSorgu = $baglanti->prepare("SELECT * FROM dbo.Urun2 WHERE KategoriID = ?");
$urunSorgu->execute([$kategori_id]);
$urunler = $urunSorgu->fetchAll(PDO::FETCH_ASSOC);
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


<section class="urunler-bolumu">
    <h2><?= $kat ? $kat['Adi'] : 'Kategori Bulunamadı' ?></h2>
    <div class="urunler-grid">
        <?php foreach ($urunler as $urun): ?>
        <div class="urun-karti">
            <img src="<?= htmlspecialchars($urun['GorselURL']) ?>" alt="<?= htmlspecialchars($urun['Adi']) ?>">
            <h4><?= htmlspecialchars($urun['Adi']) ?></h4>
            <p><?= number_format($urun['Fiyat'], 2) ?> TL</p>

            <!-- Ürün Detay Butonu -->
            <a href="urun-detay.php?id=<?= $urun['UrunID'] ?>" class="btn-detay"
               style="display:inline-block; background:#007bff; color:white; padding:8px 14px; border-radius:6px; text-decoration:none; margin-bottom:8px;">
               Ürün Detay
            </a>

            <!-- Sepete Ekle Butonu -->
            <button class="sepete-ekle"
                    data-isim="<?= htmlspecialchars($urun['Adi']) ?>"
                    data-fiyat="<?= $urun['Fiyat'] ?>"
                    data-resim="<?= htmlspecialchars($urun['GorselURL']) ?>">
                Sepete Ekle
            </button>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<script>
   document.addEventListener("DOMContentLoaded", function () {
    const slides = document.querySelectorAll(".slide");
    let currentIndex = 0;

    function showSlide(index) {
      slides.forEach((slide, i) => {
        slide.classList.remove("aktif");
        if (i === index) {
          slide.classList.add("aktif");
        }
      });
    }

    function nextSlide() {
      currentIndex = (currentIndex + 1) % slides.length;
      showSlide(currentIndex);
    }

    setInterval(nextSlide, 5000);
  });
document.addEventListener('DOMContentLoaded', function () {
  const sepeteEkleButonlari = document.querySelectorAll('.sepete-ekle');
  const odemeYapButonu = document.getElementById('odeme-yap');
  const musteriPopup = document.getElementById('musteri-popup');
  const kartPopup = document.getElementById('kart-popup');
  const musteriFormu = document.getElementById('musteri-formu');
  const kartFormu = document.getElementById('kart-formu');
  const musteriKapat = document.getElementById('musteri-kapat');
  const kartKapat = document.getElementById('kart-kapat');
  const ortaSepet = document.getElementById('orta-sepet');
  const ortaSepetKapat = document.getElementById('orta-sepet-kapat');
  const sepetAcButon = document.getElementById('sepet-ac');
  const sepetListesi = document.getElementById('sepet-listesi');

  let currentSlide = 0;
  let slides = document.querySelectorAll('.slide');
  let sepet = JSON.parse(localStorage.getItem('sepet')) || [];

  const showToast = (msg, duration = 3000) => {
    const toast = document.createElement('div');
    toast.textContent = msg;
    toast.style.position = 'fixed';
    toast.style.bottom = '30px';
    toast.style.right = '30px';
    toast.style.backgroundColor = '#323232';
    toast.style.color = '#fff';
    toast.style.padding = '10px 20px';
    toast.style.borderRadius = '6px';
    toast.style.boxShadow = '0 2px 10px rgba(0,0,0,0.2)';
    toast.style.zIndex = 10000;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), duration);
  };

  function nextSlide() {
    slides[currentSlide].classList.remove('aktif');
    currentSlide = (currentSlide + 1) % slides.length;
    slides[currentSlide].classList.add('aktif');
  }
  setInterval(nextSlide, 4000);

  function sepetiGuncelle() {
    sepetListesi.innerHTML = '';
    if (sepet.length === 0) {
      odemeYapButonu.style.display = 'none';
      return;
    }
    let toplamFiyat = 0;
    sepet.forEach(function (urun, index) {
      toplamFiyat += urun.adet * urun.fiyat;
      const li = document.createElement('li');
      li.innerHTML = `
        <div style="display:flex; align-items:center; gap:10px;">
          <img src="${urun.resim}" style="width:50px; height:50px; object-fit:cover; border-radius:5px;">
          <div>
            <strong>${urun.ad}</strong><br>
            ${urun.fiyat} TL x ${urun.adet} = <strong>${(urun.fiyat * urun.adet).toFixed(2)} TL</strong><br>
            <button title="Azalt" class="azalt" data-index="${index}" style="margin-top:5px;">-</button>
            <button title="Arttır" class="arttir" data-index="${index}" style="margin-top:5px;">+</button>
            <button title="Sil" class="sil" data-index="${index}" style="background:red; color:white; margin-top:5px;">Sil</button>
          </div>
        </div>
      `;
      sepetListesi.appendChild(li);
    });

    const toplamLi = document.createElement('li');
    toplamLi.innerHTML = `
      <div style="margin-top:20px; text-align:center;">
        <strong>Toplam Tutar: ${toplamFiyat.toFixed(2)} TL</strong><br><br>
        <button id="siparis-iptal" style="background: darkred; color: white; padding:10px 20px; border:none; border-radius:8px; cursor:pointer; font-size:16px;">🛑 Siparişimi İptal Et</button>
      </div>
    `;
    toplamLi.style.marginTop = '10px';
    toplamLi.style.borderTop = '1px solid #ccc';
    toplamLi.style.paddingTop = '10px';
    sepetListesi.appendChild(toplamLi);

    localStorage.setItem('sepet', JSON.stringify(sepet));

    const siparisIptalButonu = document.getElementById('siparis-iptal');
    if (siparisIptalButonu) {
      siparisIptalButonu.addEventListener('click', function () {
        if (confirm('Tüm siparişi iptal etmek istediğinize emin misiniz?')) {
          sepet = [];
          localStorage.removeItem('sepet');
          ortaSepet.style.display = 'none';
          odemeYapButonu.style.display = 'none';
          showToast('Siparişiniz iptal edildi.');
          sepetiGuncelle();
        }
      });
    }
  }

  sepeteEkleButonlari.forEach(function (button) {
    button.addEventListener('click', function () {
      const isim = this.getAttribute('data-isim');
      const fiyat = parseFloat(this.getAttribute('data-fiyat'));
      const resim = this.parentElement.querySelector('img').src;
      const mevcutUrun = sepet.find(urun => urun.ad === isim);

      if (mevcutUrun) {
        mevcutUrun.adet++;
      } else {
        sepet.push({ ad: isim, fiyat: fiyat, adet: 1, resim: resim });
      }

      showToast(`${isim} sepete eklendi.`);
      sepetiGuncelle();
    });
  });

  sepetListesi.addEventListener('click', function (e) {
    const index = e.target.getAttribute('data-index');
    if (!index) return;

    if (e.target.classList.contains('azalt')) {
      sepet[index].adet--;
      if (sepet[index].adet <= 0) sepet.splice(index, 1);
    } else if (e.target.classList.contains('arttir')) {
      sepet[index].adet++;
    } else if (e.target.classList.contains('sil')) {
      sepet.splice(index, 1);
    }
    sepetiGuncelle();
  });

  odemeYapButonu.addEventListener('click', function () {
    if (sepet.length === 0) {
      alert("Sepetiniz boş!");
    } else {
      musteriPopup.style.display = 'flex';
    }
  });

  musteriFormu.addEventListener('submit', function (e) {
    e.preventDefault();
    musteriPopup.style.display = 'none';
    kartPopup.style.display = 'flex';
  });

  kartFormu.addEventListener('submit', function (e) {
    e.preventDefault();
    alert("Ödeme başarılı! 🎉 Siparişiniz alınmıştır!");
    sepet = [];
    localStorage.removeItem('sepet');
    kartPopup.style.display = 'none';
    odemeYapButonu.style.display = 'none';
    ortaSepet.style.display = 'none';
    showToast('Ödeme başarılı. Siparişiniz alındı.');
    sepetiGuncelle();
  });

  musteriKapat.addEventListener('click', function () {
    musteriPopup.style.display = 'none';
  });

  kartKapat.addEventListener('click', function () {
    kartPopup.style.display = 'none';
  });

  sepetAcButon.addEventListener('click', function () {
    ortaSepet.style.display = 'block';
    odemeYapButonu.style.display = 'inline-block';
    window.scrollTo({ top: ortaSepet.offsetTop - 100, behavior: 'smooth' });
  });

  ortaSepetKapat?.addEventListener('click', function () {
    ortaSepet.style.display = 'none';
    odemeYapButonu.style.display = 'none';
  });

  sepetiGuncelle();
});


</script>
<?php include("partials/footer.php"); ?>
<script src="sepet.js"></script>
</body>
</html>
