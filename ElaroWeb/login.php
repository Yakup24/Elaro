<?php
require_once __DIR__ . '/security.php';

if (isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

require_once __DIR__ . '/db.php';
$conn = $baglanti;

// Kayıt mesajı kontrolü
$mesaj = "";
if (isset($_SESSION['kayit_mesaji'])) {
    $mesaj = $_SESSION['kayit_mesaji'];
    unset($_SESSION['kayit_mesaji']);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_csrf();

    $email = $_POST["email"];
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT MusteriID, Ad, Soyad, Eposta, Sifre, [Role] FROM dbo.[Musteri2] WHERE Eposta = :email");
    $stmt->bindParam(":email", $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    $storedPassword = $user["Sifre"] ?? "";
    $passwordValid = $user && password_verify($password, $storedPassword);

    if ($passwordValid) {
        session_regenerate_id(true);
        $_SESSION['username'] = $user["Ad"] . ' ' . $user["Soyad"];
        $_SESSION['giris_tarihi'] = date("Y-m-d H:i:s");

        if (strcasecmp((string)($user["Role"] ?? ''), 'Admin') === 0) {
            $_SESSION["admin_id"] = $user["MusteriID"];
            $_SESSION["admin_eposta"] = $user["Eposta"];
            $_SESSION["admin_role"] = "Admin";
            $_SESSION["admin_kadi"] = trim(($user["Ad"] ?? '') . ' ' . ($user["Soyad"] ?? ''));
            header("Location: admin/panel.php");
        } else {
            header("Location: index.php");
        }

        exit();
    } else {
        $error = "Geçersiz e-posta veya şifre.";
    }
}
?>


<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giriş Yap</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 350px;
            text-align: center;
        }

        h2 {
            color: #333;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: bold;
            text-align: left;
        }

        input[type=text], input[type=password] {
            width: calc(100% - 22px);
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 16px;
        }

        button {
            background-color: #007bff;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            font-size: 18px;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        .error {
            color: #dc3545;
            margin-top: 10px;
        }

        p {
            margin-top: 20px;
            color: #777;
        }

        p a {
            color: #007bff;
            text-decoration: none;
        }

        p a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Giriş Yap</h2>
        <?php if (!empty($mesaj)): ?>
            <p style="color: green;"><?php echo $mesaj; ?></p>
        <?php endif; ?>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form method="post">
            <?= csrf_field() ?>
            <label for="email">E-Posta:</label>
            <input type="text" id="email" name="email" required>
            <label for="password">Sifre:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit">Giriş</button>
        </form>
        <p><a href="kayit.php">Kayıt Ol</a></p>
        <p><a href="index.php">Anasayfa</a></p>
    </div>
</body>
</html>
