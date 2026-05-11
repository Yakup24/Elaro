<?php
session_start();

if (!isset($_SESSION['sepet'])) {
    $_SESSION['sepet'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $urun = [
        'ad' => $_POST['ad'],
        'fiyat' => $_POST['fiyat'],
        'resim' => $_POST['resim']
    ];

    foreach ($_SESSION['sepet'] as $mevcut) {
        if ($mevcut['ad'] === $urun['ad']) {
            echo json_encode(['success' => true]);
            exit;
        }
    }

    $_SESSION['sepet'][] = $urun;
    echo json_encode(['success' => true]);
}
