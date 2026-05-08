<?php
// Composer autoload
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.html');
    exit;
}

$dsn = 'mysql:host=localhost;dbname=email_logs_db;charset=utf8';
$username = 'root';
$password = '';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Veritabanı bağlantısı başarısız oldu: " . htmlspecialchars($e->getMessage()));
}

// Toplu Silme İşlemi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_ids'])) {
    $idsToDelete = $_POST['delete_ids'];
    $placeholders = implode(',', array_fill(0, count($idsToDelete), '?'));

    // Pozitif ve negatif yanıtlar için silme sorguları
    $deleteQueryPositive = "DELETE FROM positive_responses WHERE id IN ($placeholders)";
    $deleteQueryNegative = "DELETE FROM negative_responses WHERE id IN ($placeholders)";

    $statementPositive = $pdo->prepare($deleteQueryPositive);
    $statementNegative = $pdo->prepare($deleteQueryNegative);

    foreach ($idsToDelete as $index => $id) {
        $statementPositive->bindValue($index + 1, $id, PDO::PARAM_INT);
        $statementNegative->bindValue($index + 1, $id, PDO::PARAM_INT);
    }

    $statementPositive->execute();
    $statementNegative->execute();
}

$filter = $_GET['filter'] ?? 'all';
$emailSearch = $_GET['email_search'] ?? '';

$query = "";
$params = [];

if ($filter === 'positive') {
    $query = "SELECT * FROM positive_responses";
} elseif ($filter === 'negative') {
    $query = "SELECT * FROM negative_responses";
} else {
    $query = "SELECT * FROM positive_responses UNION SELECT * FROM negative_responses";
}

if (!empty($emailSearch)) {
    $query .= " WHERE email LIKE :email";
    $params[':email'] = "%$emailSearch%";
}

$statement = $pdo->prepare($query);

foreach ($params as $key => $value) {
    $statement->bindValue($key, $value);
}

$statement->execute();
$records = $statement->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>İK Yanıt Yönetimi</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">İK Yönetimi</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php?filter=all">Tüm Yanıtlar</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?filter=positive">Olumlu Yanıtlar</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?filter=negative">Olumsuz Yanıtlar</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="form.php">Yanıt Gönder</a>
                </li>
            </ul>
            <br> <br>
    </nav>

    <div class="container mt-5">
        <h2>
            <?php 
            if ($filter === 'positive') {
                echo 'Olumlu Yanıtlar';
            } elseif ($filter === 'negative') {
                echo 'Olumsuz Yanıtlar';
            } else {
                echo 'Tüm Yanıtlar';
            }
            ?>
        </h2>
        <form method="post" action="index.php">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="select-all"></th>
                        <th>ID</th>
                        <th>Ad</th>
                        <th>Soyad</th>
                        <th>E-posta</th>
                        <th>Şirket</th>
                        <th>İletişim Tarihi</th>
                        <th>Mülakat Yeri</th>
                        <th>Durum</th>
                        <th>Pozisyon</th>
                        <th>Zaman Damgası</th>
                        <th>İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($records) > 0): ?>
                        <?php foreach ($records as $record): ?>
                            <tr>
                                <td><input type="checkbox" name="delete_ids[]" value="<?php echo htmlspecialchars($record['id']); ?>"></td>
                                <td><?php echo htmlspecialchars($record['id']); ?></td>
                                <td><?php echo htmlspecialchars($record['first_name']); ?></td>
                                <td><?php echo htmlspecialchars($record['last_name']); ?></td>
                                <td><?php echo htmlspecialchars($record['email']); ?></td>
                                <td><?php echo htmlspecialchars($record['company']); ?></td>
                                <td><?php echo htmlspecialchars($record['contact_date']); ?></td>
                                <td><?php echo htmlspecialchars($record['interview_location']); ?></td>
                                <td><?php echo htmlspecialchars($record['status']); ?></td>
                                <td><?php echo htmlspecialchars($record['position']); ?></td>
                                <td><?php echo htmlspecialchars($record['timestamp']); ?></td>
                                <td>
                                    <button type="submit" formaction="delete.php?id=<?php echo htmlspecialchars($record['id']); ?>" class="btn btn-danger btn-sm">Sil</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="12" class="text-center">Eşleşen kayıt bulunamadı</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <button type="submit" class="btn btn-danger">Seçilenleri Sil</button>
        </form>
    </div>

    <script>
        document.getElementById('select-all').onclick = function() {
            var checkboxes = document.getElementsByName('delete_ids[]');
            for (var checkbox of checkboxes) {
                checkbox.checked = this.checked;
            }
        }
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
