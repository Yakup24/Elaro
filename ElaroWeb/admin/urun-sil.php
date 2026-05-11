<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method not allowed.');
}

require_csrf();

if (isset($_POST['id'])) {
    try {
        $id = (int) $_POST['id'];
        $sorgu = $baglanti->prepare("DELETE FROM [Urun2] WHERE UrunID = ?");
        $sorgu->execute([$id]);
        echo "Silme başarılı.";
    } catch (PDOException $e) {
        error_log("Product delete failed: " . $e->getMessage());
        exit;
    }
}

header("Location: panel.php");
exit;
