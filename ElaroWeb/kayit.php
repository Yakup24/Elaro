<?php
require_once __DIR__ . '/security.php';

// PHP'de saat dilimini Türkiye'ye ayarla
date_default_timezone_set('Europe/Istanbul');

require_once __DIR__ . '/db.php';
$conn = $baglanti;

if (isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_csrf();

    $ad = $_POST["username"] ?? '';
    $soyad = $_POST["soyad"] ?? '';
    $email = $_POST["email"] ?? '';
    $cinsiyet = $_POST["cinsiyet"] ?? '';
    $dogumTarihi = $_POST["dogum_tarihi"] ?? '';
    $password = $_POST["password"] ?? '';
    $kayitTarihi = date("Y-m-d H:i:s"); // Türkiye saatine göre tarih

    // E-posta zaten kayıtlı mı kontrol et
    $kontrol = $conn->prepare("SELECT COUNT(*) FROM dbo.[Musteri2] WHERE Eposta = :email");
    $kontrol->bindParam(':email', $email);
    $kontrol->execute();
    $varMi = $kontrol->fetchColumn();

    if ($varMi > 0) {
        $error = "Bu e-posta adresi zaten kayıtlı. Lütfen farklı bir e-posta girin.";
    } else {
        try {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);


            $role = 'Customer';
            $stmt = $conn->prepare("INSERT INTO dbo.[Musteri2] (Ad, Soyad, Eposta, Sifre, KayitTarihi, Cinsiyet, DogumTarihi, [Role])
                                    VALUES (:ad, :soyad, :email, :password, :kayitTarihi, :cinsiyet, :dogumTarihi, :role)");

            $stmt->bindParam(':ad', $ad);
            $stmt->bindParam(':soyad', $soyad);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':kayitTarihi', $kayitTarihi);
            $stmt->bindParam(':cinsiyet', $cinsiyet);
            $stmt->bindParam(':dogumTarihi', $dogumTarihi);
            $stmt->bindParam(':role', $role);

            $stmt->execute();

            $_SESSION['kayit_mesaji'] = "Kaydınız başarıyla tamamlandı. Giriş yapabilirsiniz.";
            header("Location: login.php");
            exit();

        } catch (PDOException $e) {
            error_log("Registration failed: " . $e->getMessage());
            $error = "Kayıt sırasında bir hata oluştu.";
        }
    }
}
?>



<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kayıt Ol</title>
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

        input[type=text], input[type=password], input[type=email], input[type=date], select {
            width: calc(100% - 22px);
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 16px;
        }

        button {
            background-color: #28a745;
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
            background-color: #1e7e34;
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
        <h2>Kayıt Ol</h2>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form method="post">
            <?= csrf_field() ?>
            <label for="username">Ad:</label>
            <input type="text" id="username" name="username" required>
            <label for="soyad">Soyad:</label>
            <input type="text" id="soyad" name="soyad" required>
            <label for="email">E-Posta:</label>
            <input type="email" id="email" name="email" required>
            <label for="cinsiyet">Cinsiyet:</label>
            <select id="cinsiyet" name="cinsiyet">
                <option value="Erkek">Erkek</option>
                <option value="Kadın">Kadın</option>
                <option value="Belirtmek İstemiyorum">Belirtmek İstemiyorum</option>
            </select>
            <label for="dogum_tarihi">Doğum Tarihi:</label>
            <input type="date" id="dogum_tarihi" name="dogum_tarihi">
            <label for="password">Sifre:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit">Kayıt Ol</button>
        </form>
        <p>Zaten bir hesabınız var mı? <a href="login.php">Giriş Yap</a></p>
    </div>
</body>
</html>
