<?php
include("db.php");
session_start();

if (isset($_GET['id'])) {
    try {
        $sorgu = $baglanti->prepare("DELETE FROM [Urun2] WHERE UrunID = ?");
        $sorgu->execute([$_GET['id']]);
        echo "Silme başarılı.";
    } catch (PDOException $e) {
        echo "Silme Hatası: " . $e->getMessage();
        exit;
    }
}

header("Location: panel.php");
exit;
