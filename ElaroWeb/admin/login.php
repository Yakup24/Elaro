<?php
require_once __DIR__ . '/db.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    $secureCookie = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (($_SERVER['SERVER_PORT'] ?? null) === '443');

    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => $secureCookie,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);

    session_start();
}

$adminEmail = getenv('ELARO_ADMIN_EMAIL') ?: 'admin@elaro.com';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $eposta = $_POST["Eposta"] ?? '';
    $sifre = $_POST["Sifre"] ?? '';

    $sorgu = $baglanti->prepare("SELECT * FROM dbo.Musteri2 WHERE Eposta = ?");
    $sorgu->execute([$eposta]);
    $kullanici = $sorgu->fetch(PDO::FETCH_ASSOC);

    $storedPassword = $kullanici["Sifre"] ?? "";
    $passwordValid = $kullanici && (password_verify($sifre, $storedPassword) || hash_equals($storedPassword, $sifre));

    if ($passwordValid && hash_equals($adminEmail, $eposta)) {
        if (hash_equals($storedPassword, $sifre)) {
            $newHash = password_hash($sifre, PASSWORD_DEFAULT);
            $update = $baglanti->prepare("UPDATE dbo.Musteri2 SET Sifre = ? WHERE MusteriID = ?");
            $update->execute([$newHash, $kullanici["MusteriID"]]);
        }

        session_regenerate_id(true);
        $_SESSION["admin_id"] = $kullanici["MusteriID"];
        $_SESSION["admin_eposta"] = $kullanici["Eposta"];
        $_SESSION["admin_kadi"] = trim(($kullanici["Ad"] ?? '') . ' ' . ($kullanici["Soyad"] ?? ''));
        header("Location: panel.php");
        exit;
    } else {
        $hata = "Eposta veya sifre yanlis!";
    }
}
?>

<form method="post">
  <input name="Eposta" placeholder="E-posta" required>
  <input name="Sifre" type="password" placeholder="Sifre" required>
  <button>Giris Yap</button>
</form>

<?php if (isset($hata)) echo "<p style='color:red;'>" . htmlspecialchars($hata) . "</p>"; ?>
