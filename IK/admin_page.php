<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$dsn = 'mysql:host=localhost;dbname=email_logs_db;charset=utf8';
$db_username = 'root';
$db_password = '';

try {
    $pdo = new PDO($dsn, $db_username, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . htmlspecialchars($e->getMessage()));
}

if (isset($_POST['delete'])) {
    $ids = $_POST['ids'];
    if (!empty($ids)) {
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $stmt = $pdo->prepare("DELETE FROM users WHERE id IN ($placeholders)");
        $stmt->execute($ids);
    }
}

$stmt = $pdo->query("SELECT * FROM users");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Page</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <h1 class="mt-5">Admin Page</h1>
    <form method="post" action="admin_page.php">
        <table class="table table-striped mt-3">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Username</th>
                <th scope="col"><input type="checkbox" id="select-all"></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <th scope="row"><?php echo htmlspecialchars($user['id']); ?></th>
                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                    <td><input type="checkbox" name="ids[]" value="<?php echo $user['id']; ?>"></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <button type="submit" name="delete" class="btn btn-danger">Delete Selected</button>
    </form>
</div>

<script>
    document.getElementById('select-all').onclick = function() {
        var checkboxes = document.getElementsByName('ids[]');
        for (var checkbox of checkboxes) {
            checkbox.checked = this.checked;
        }
    }
</script>
</body>
</html>
