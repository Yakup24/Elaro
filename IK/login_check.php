<?php
session_start();

// Kullanıcı oturumu kontrolü
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // Kullanıcı giriş yapmamışsa giriş sayfasına yönlendir
    header('Location: login.html');
    exit();
}
?>
