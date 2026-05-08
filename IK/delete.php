<?php
$dsn = 'mysql:host=localhost;dbname=email_logs_db;charset=utf8';
$username = 'root';
$password = '';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Veritabanı bağlantısı başarısız oldu: " . htmlspecialchars($e->getMessage()));
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $deleteQueryPositive = "DELETE FROM positive_responses WHERE id = :id";
    $deleteQueryNegative = "DELETE FROM negative_responses WHERE id = :id";

    $statementPositive = $pdo->prepare($deleteQueryPositive);
    $statementNegative = $pdo->prepare($deleteQueryNegative);

    $statementPositive->bindValue(':id', $id, PDO::PARAM_INT);
    $statementNegative->bindValue(':id', $id, PDO::PARAM_INT);

    $statementPositive->execute();
    $statementNegative->execute();
}

header("Location: index.php");
exit();
?>
