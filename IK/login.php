<?php
session_start();
require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$dsn = 'mysql:host=localhost;dbname=email_logs_db;charset=utf8';
$db_username = 'root';
$db_password = '';

try {
    $pdo = new PDO($dsn, $db_username, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . htmlspecialchars($e->getMessage()));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Debugging: Output submitted credentials
    echo "Submitted Username: " . htmlspecialchars($username) . "<br>";
    echo "Submitted Password: " . htmlspecialchars($password) . "<br>";

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username LIMIT 1");
    $stmt->execute([':username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Debugging: Output retrieved user data
    echo "Retrieved User Data: " . print_r($user, true) . "<br>";

    if ($user && $password == $user['password']) { // Passwords are not hashed
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header('Location: index.php'); // Redirect to the main page
        exit();
    } else {
        echo "Invalid username or password.";
    }
}
?>
