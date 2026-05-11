<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../db.php';

if (!isset($_POST['id'], $_POST['durum'])) {
    header("Location: siparisler.php");
    exit;
}

$id = (int) $_POST['id'];
$durum = $_POST['durum'];

$stmt = $pdo->prepare("UPDATE [Siparis2] SET [SiparisDurumu] = ? WHERE [SiparisID] = ?");
$stmt->execute([$durum, $id]);

header("Location: siparisler.php");
exit;
