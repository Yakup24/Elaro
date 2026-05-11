<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/db.php';

if (isset($_GET['id'])) {
    try {
        $sorgu = $baglanti->prepare("DELETE FROM [Urun2] WHERE UrunID = ?");
        $sorgu->execute([$_GET['id']]);
        echo "Silme başarılı.";
    } catch (PDOException $e) {
        error_log("Product delete failed: " . $e->getMessage());
        exit;
    }
}

header("Location: panel.php");
exit;
