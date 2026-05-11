<div class="category-menu">
    <ul class="menu-list">
        <li class="menu-item"><a href="index.php">Ana Sayfa</a></li>
        <li class="menu-item"><a href="kadin.php">Kadın</a>
            <ul class="submenu">
                <li class="submenu-item"><a href="elbiseler.php">Elbiseler</a></li>
                <li class="submenu-item"><a href="ayakkabılar.php">Ayakkabılar</a></li>
                <li class="submenu-item"><a href="cantalar.php">Çantalar</a></li>
                <li class="submenu-item"><a href="Aksesuarlar.php">Aksesuarlar</a></li>
            </ul>
        </li>
        <li class="menu-item"><a href="erkek.php">Erkek</a>
            <ul class="submenu">
                <li class="submenu-item"><a href="gomlek.php">Gömlek</a></li>
                <li class="submenu-item"><a href="pantolon.php">Pantolon</a></li>
                <li class="submenu-item"><a href="ceket.php">Ceket</a></li>
                <li class="submenu-item"><a href="ayakkabılar2.php">Ayakkabılar</a></li>
            </ul>
        </li>
        <li class="menu-item has-submenu">
            <a href="cocuk.php">Çocuk</a>
            <ul class="submenu">
                <li class="submenu-item has-subcategory">
                    <a href="kızcocuk.php">Kız Çocuk</a>
                    <div class="subcategory">
                        <a href="KıyafetlerKÇ.php">Kıyafetler</a>
                        <a href="AyakkabılarKÇ.php">Ayakkabılar</a>
                        <a href="AksesuarlarKÇ.php">Aksesuarlar</a>

                    </div>
                </li>
                <li class="submenu-item has-subcategory">
                    <a href="erkekcocuk.php">Erkek Çocuk</a>
                    <div class="subcategory">
                        <a href="KıyafetlerEÇ.php">Kıyafetler</a>
                        <a href="AyakkabılarEÇ.php">Ayakkabılar</a>
                        <a href="AksesuarlarEÇ.php">Aksesuarlar</a>

                    </div>
                </li>

            </ul>
        </li>
        <li class="menu-item"><a href="aksesuarlar2.php">Aksesuarlar</li>
        <li class="menu-item">
            <a href="#">Hesabım</a>
            <ul class="submenu">
                <?php if (isset($_SESSION['username'])): ?>
                    <li class="submenu-item"><span class="welcome-text">Hoş geldiniz, <?php echo htmlspecialchars($_SESSION['username']); ?></span></li>
                    <li class="submenu-item"><a href="cikis.php">Çıkış Yap</a></li>
                <?php else: ?>
                    <li class="submenu-item"><a href="login.php">Giriş Yap</a></li>
                    <li class="submenu-item"><a href="kayıt.php">Kayıt Ol</a></li>
                <?php endif; ?>
            </ul>
        </li>
    </ul>
</div>

<div class="kargo-container">

    <img id="kargo-araci" src="https://i.hizliresim.com/kpxf42m.png" alt="Kargo Aracı" style="height: 40px;">
    <span id="kargo-yazisi">Siparişiniz 24 Saatte Kapınızda</span>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const kargoAraci = document.getElementById('kargo-araci');
        const kargoYazisi = document.getElementById('kargo-yazisi');
        let animasyonDevamEdiyor = false;
        let animasyonZamanlayici;

        function baslatKargoAnimasyonu() {
            if (animasyonDevamEdiyor) return;
            animasyonDevamEdiyor = true;

            // Animasyonu başlat
            kargoAraci.style.transition = 'left 5s linear';
            kargoAraci.style.left = 'calc(100% + 100px)';
            kargoYazisi.style.opacity = 1;
            kargoYazisi.style.left = '41%';

            // 7 saniye sonra yazıyı sakla ve animasyonu tekrar başlat
            animasyonZamanlayici = setTimeout(function() {
                kargoYazisi.style.opacity = 0;
                setTimeout(sifirlaKargoAnimasyonu, 500);
            }, 5000 + 4000); // 5 saniye (animasyon süresi) + 7 saniye (bekleme süresi)
        }

        function sifirlaKargoAnimasyonu() {
            clearTimeout(animasyonZamanlayici);
            animasyonDevamEdiyor = false;
            kargoAraci.style.transition = 'none';
            kargoAraci.style.left = '-100px';
            kargoYazisi.style.transition = 'none';
            kargoYazisi.style.left = '-40%';
            kargoYazisi.style.opacity = 0;
            setTimeout(() => {
                kargoYazisi.style.opacity = 0;
                kargoAraci.style.left = '-100px';
                kargoYazisi.style.transition = 'left 4s linear';
                kargoYazisi.style.opacity = 1;
                kargoYazisi.style.left = '41%';
                setTimeout(baslatKargoAnimasyonu, 100);
            }, 100);
        }

        // Animasyonu başlat
        setTimeout(baslatKargoAnimasyonu, 100); // Sayfa yüklendikten 0.1 saniye sonra başlat
    });
</script>