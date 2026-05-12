<?php
require_once __DIR__ . '/security.php';
require_once __DIR__ . '/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_csrf();

    $eposta = $_POST["Eposta"] ?? '';
    $sifre = $_POST["Sifre"] ?? '';

    $sorgu = $baglanti->prepare("SELECT MusteriID, Eposta, Ad, Soyad, Sifre, [Role] FROM dbo.Musteri2 WHERE Eposta = ?");
    $sorgu->execute([$eposta]);
    $kullanici = $sorgu->fetch(PDO::FETCH_ASSOC);

    $storedPassword = $kullanici["Sifre"] ?? "";
    $passwordValid = $kullanici && password_verify($sifre, $storedPassword);
    $isAdmin = $kullanici && strcasecmp((string)($kullanici["Role"] ?? ''), 'Admin') === 0;

    if ($passwordValid && $isAdmin) {
        session_regenerate_id(true);
        $_SESSION["admin_id"] = $kullanici["MusteriID"];
        $_SESSION["admin_eposta"] = $kullanici["Eposta"];
        $_SESSION["admin_role"] = "Admin";
        $_SESSION["admin_kadi"] = trim(($kullanici["Ad"] ?? '') . ' ' . ($kullanici["Soyad"] ?? ''));
        header("Location: panel.php");
        exit;
    } else {
        $hata = "Eposta veya sifre yanlis!";
    }
}
?>

<form method="post">
  <?= csrf_field() ?>
  <input name="Eposta" placeholder="E-posta" required>
  <input name="Sifre" type="password" placeholder="Sifre" required>
  <button>Giris Yap</button>
</form>

<?php if (isset($hata)) echo "<p style='color:red;'>" . htmlspecialchars($hata) . "</p>"; ?>
