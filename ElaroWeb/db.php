<?php
$host = getenv('ELARO_DB_HOST') ?: 'localhost';
$dbname = getenv('ELARO_DB_NAME') ?: 'Elaro';
$username = getenv('ELARO_DB_USER') ?: '';
$password = getenv('ELARO_DB_PASSWORD') ?: '';

$dsn = "sqlsrv:Server=$host;Database=$dbname";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    if ($username === '' && $password === '') {
        $baglanti = new PDO($dsn, null, null, $options);
    } else {
        $baglanti = new PDO($dsn, $username, $password, $options);
    }

    $pdo = $baglanti;
} catch (PDOException $e) {
    error_log('Database connection failed: ' . $e->getMessage());
    http_response_code(500);
    die('Database connection is not configured.');
}
