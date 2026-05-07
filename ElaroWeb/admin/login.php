<?php
include("db.php");
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $eposta = $_POST["Eposta"];
    $sifre = $_POST["Sifre"];

    // Musteri2 tablosundan eposta ve şifre ile kullanıcıyı kontrol et
    $sorgu = $baglanti->prepare("SELECT * FROM dbo.Musteri2 WHERE Eposta = ? AND Şifre = ?");
    $sorgu->execute([$eposta, $sifre]);
    $kullanici = $sorgu->fetch(PDO::FETCH_ASSOC);

    if ($kullanici) {
        $_SESSION["admin_id"] = $kullanici["MusteriID"];
        $_SESSION["admin_eposta"] = $kullanici["Eposta"];
        header("Location: panel.php");
        exit;
    } else {
        $hata = "Eposta veya şifre yanlış!";
    }
}
?>

<!-- Giriş Formu -->
<form method="post">
  <input name="Eposta" placeholder="E-posta" required>
  <input name="Sifre" type="password" placeholder="Şifre" required>
  <button>Giriş Yap</button>
</form>

<?php if (isset($hata)) echo "<p style='color:red;'>$hata</p>"; ?>
