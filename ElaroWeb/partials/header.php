<header class="header">
    <div id="markaFont">
        <a href="index.php">
            <img src="https://i.hizliresim.com/4yoylik.png" alt="PikPazar Banner" class="banner-resmi" >
        </a>
    </div>

    <div class="arama-alani">
        <form id="aramaFormu" action="arama.php" method="GET">
            <input type="text" id="aramaInput" name="q" placeholder="Ürün ara..." required>
            <button type="submit">Ara</button>
        </form>
    </div>

    <div class="header-sag">
        <div class="mod-degistirici">
            <button id="gunduzModu" onclick="gunduzModunaGec()" title="Gündüz Modu">☀️</button>
            <button id="geceModu" onclick="geceModunaGec()" title="Gece Modu">🌙</button>
            <a href="sepet.php" class="sepet-buton" style="text-decoration: none;">
  🛒 Sepetim
</a>
        </div>
    </div>
</header>