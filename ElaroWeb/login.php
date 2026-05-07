<?php
session_start();

if (isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// Veritabanı bağlantısı
$serverName = "elaro.database.windows.net";
$databaseName = "elaro";
$userName = "elaroadmin";
$password = "M3sl3ki.proje";

try {
    $conn = new PDO(
        "sqlsrv:Server=$serverName;Database=$databaseName",
        $userName,
        $password
    );
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Veritabanı bağlantı hatası: " . $e->getMessage());
}

// Kayıt mesajı kontrolü
$mesaj = "";
if (isset($_SESSION['kayit_mesaji'])) {
    $mesaj = $_SESSION['kayit_mesaji'];
    unset($_SESSION['kayit_mesaji']);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT * FROM dbo.[Musteri2] WHERE Eposta = :email");
    $stmt->bindParam(":email", $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // DÜZENLENEN ŞİFRE KONTROLÜ (düz metin)
   // DÜZENLENEN ŞİFRE KONTROLÜ (düz metin)
if ($user && $password === $user["Sifre"]) {
    $_SESSION['username'] = $user["Ad"] . ' ' . $user["Soyad"];
    $_SESSION['giris_tarihi'] = date("Y-m-d H:i:s");

    // Eğer giriş yapan admin ise, admin paneline yönlendir
    if ($user["Eposta"] === "admin@elaro.com") {
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
            <label for="email">E-Posta:</label>
            <input type="text" id="email" name="email" required>
            <label for="password">Sifre:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit">Giriş</button>
        </form>
        <p><a href="kayıt.php">Kayıt Ol</a></p>
        <p><a href="index.php">Anasayfa</a></p>
    </div>
</body>
</html>
