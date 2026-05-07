<?php
session_start();


include("db.php");

$tarihBasla = $_GET['basla'] ?? null;
$tarihBitir = $_GET['bitir'] ?? null;

if ($tarihBasla && $tarihBitir) {
    $stmt = $baglanti->prepare("SELECT S.[SiparisID], S.[SiparisTarihi], S.[ToplamTutar], S.[SiparisDurumu],
                                M.[Ad] AS MusteriAd, M.[Soyad] AS MusteriSoyad
                                FROM [Siparis2] S
                                JOIN [Musteri2] M ON S.[MusteriID] = M.[MusteriID]
                                WHERE S.[SiparisTarihi] BETWEEN ? AND ?
                                ORDER BY S.[SiparisTarihi] DESC");
    $stmt->execute([$tarihBasla, $tarihBitir]);
} else {
    $stmt = $baglanti->query("SELECT S.[SiparisID], S.[SiparisTarihi], S.[ToplamTutar], S.[SiparisDurumu],
                              M.[Ad] AS MusteriAd, M.[Soyad] AS MusteriSoyad
                              FROM [Siparis2] S
                              JOIN [Musteri2] M ON S.[MusteriID] = M.[MusteriID]
                              ORDER BY S.[SiparisTarihi] DESC");
}
$siparisler = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Siparişler</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h2 class="mb-4">Sipariş Listesi</h2>

    <form method="get" class="row g-3 mb-4">
        <div class="col-md-4">
            <label class="form-label">Başlangıç Tarihi</label>
            <input type="date" name="basla" class="form-control" value="<?= htmlspecialchars($tarihBasla) ?>">
        </div>
        <div class="col-md-4">
            <label class="form-label">Bitiş Tarihi</label>
            <input type="date" name="bitir" class="form-control" value="<?= htmlspecialchars($tarihBitir) ?>">
        </div>
        <div class="col-md-4 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100">Filtrele</button>
        </div>
    </form>

    <table class="table table-bordered table-striped bg-white shadow-sm">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Müşteri</th>
                <th>Tarih</th>
                <th>Toplam</th>
                <th>Durum</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($siparisler as $siparis): ?>
                <tr>
                    <td><?= $siparis['SiparisID'] ?></td>
                    <td><?= htmlspecialchars($siparis['MusteriAd'] . " " . $siparis['MusteriSoyad']) ?></td>
                    <td><?= $siparis['SiparisTarihi'] ?></td>
                    <td><?= number_format($siparis['ToplamTutar'], 2) ?> TL</td>
                    <td><?= $siparis['SiparisDurumu'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <a href="panel.php" class="btn btn-secondary mt-3">Panele Dön</a>
</div>

</body>
</html>
