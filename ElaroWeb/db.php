<?php
$host = 'elaro.database.windows.net';
$dbname = 'elaro';
$username = 'elaroadmin';
$password = 'M3sl3ki.proje';

try {
    $baglanti = new PDO("sqlsrv:Server=$host;Database=$dbname", $username, $password);
    $baglanti->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Bağlantı hatası: " . $e->getMessage());
}
?>

?>