<?php
session_start();

// Tüm oturum değişkenlerini sil
$_SESSION = array();

// Oturumu sonlandır
session_destroy();

// Oturumla ilgili çerezi sil (güvenli çıkış için)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Eğer özel olarak tanımlanmış başka çerezler varsa onları da silebilirsiniz:
// Örnek:
// if (isset($_COOKIE['kullanici'])) {
//     setcookie('kullanici', '', time() - 3600, '/');
// }

header("Location: login.php"); // Giriş sayfasına yönlendir
exit();
?>
