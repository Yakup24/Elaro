<?php
// admin/siparis-durum.php
session_start();
require_once '../db.php';

if (!isset($_SESSION['admin']) || !isset($_POST['id'], $_POST['durum'])) {
    header("Location: siparisler.php");
    exit;
}

$id = (int) $_POST['id'];
$durum = $_POST['durum'];

$stmt = $pdo->prepare("UPDATE Siparis SET Durum = ? WHERE SiparisID = ?");
$stmt->execute([$durum, $id]);

header("Location: siparisler.php");
exit;
