<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $isim = $_POST['ad'] ?? '';
    $fiyat = floatval($_POST['fiyat'] ?? 0);
    $resim = $_POST['resim'] ?? '';

    if (!isset($_SESSION['sepet'])) $_SESSION['sepet'] = [];

    // Aynı ürün varsa adet arttır
    foreach ($_SESSION['sepet'] as &$urun) {
        if ($urun['ad'] === $isim) {
            $urun['adet'] += 1;
            echo json_encode(['success' => true]);
            exit;
        }
    }

    // Yeni ürün ekle
    $_SESSION['sepet'][] = [
        'ad' => $isim,
        'fiyat' => $fiyat,
        'resim' => $resim,
        'adet' => 1
    ];

    echo json_encode(['success' => true]);
}
