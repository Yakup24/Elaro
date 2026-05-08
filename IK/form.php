<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.html');
    exit;
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>İK Yanıt Formu</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8f9fa;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #333333;
            margin-bottom: 20px;
            font-size: 1.25rem;
        }
        form {
            margin-top: 10px;
        }
        label {
            font-weight: bold;
            color: #555555;
            display: block;
            margin-bottom: 5px;
            font-size: 0.875rem;
        }
        input[type=text], input[type=email], input[type=datetime-local], select {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #cccccc;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 0.875rem;
        }
        .submit-btn {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            font-size: 0.875rem;
            font-weight: bold;
            text-transform: uppercase;
            display: inline-block;
            width: 100%;
            margin-top: 10px;
        }
        .submit-btn:hover {
            background-color: #0056b3;
        }
        .custom-select {
            display: block;
            width: 100%;
            padding: 8px;
            font-size: 0.875rem;
            border: 1px solid #cccccc;
            border-radius: 5px;
            box-sizing: border-box;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-color: #f9f9f9;
            background-image: url('data:image/svg+xml;utf8,<svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M7.41 8.59L12 13.17l4.59-4.58L18 10l-6 6-6-6 1.41-1.41z"/></svg>');
            background-repeat: no-repeat;
            background-position-x: calc(100% - 10px);
            background-position-y: center;
            cursor: pointer;
        }
        .footer {
            margin-top: 15px;
            text-align: center;
            font-size: 0.75rem;
            color: #666666;
        }
        .footer img.banner {
            max-width: 100%; 
            height: auto;
            margin-bottom: 10px;
        }
        .hidden {
            display: none;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <img src="https://i.hizliresim.com/bcb84v2.png" alt="Banner" class="banner"> 

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="login.html">Ana Sayfa</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Geri Tablo Ekranına</a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container">
        <h2>İnsan Kaynakları Yanıt Formu</h2>
        <form id="ik-form" method="post" action="send_email.php">
            <label for="isim">Ad:</label>
            <input type="text" id="isim" name="isim" required>

            <label for="soyisim">Soyad:</label>
            <input type="text" id="soyisim" name="soyisim" required>

            <label for="eposta">E-posta Adresi:</label>
            <input type="email" id="eposta" name="eposta" required>

            <label for="sirket">Şirket:</label>
            <select id="sirket" name="sirket" class="custom-select" required>
                <option value="">Şirket Seçiniz</option>
                <option value="Trendyol">Trendyol</option>
                <option value="bcc">bcc</option>
                <option value="Turkcell">Turkcell</option>
            </select>

            <label for="pozisyon">Pozisyon:</label>
            <select id="pozisyon" name="pozisyon" class="custom-select" required>
                <option value="">Pozisyon Seçiniz</option>
                <option value="Yazılım Geliştirici">Yazılım Geliştirici</option>
                <option value="Pazarlama Uzmanı">Pazarlama Uzmanı</option>
                <option value="Proje Yöneticisi">Proje Yöneticisi</option>
            </select>

            <label for="yanit">Başvuru Durumu:</label>
            <select id="yanit" name="yanit" class="custom-select" onchange="toggleFields()" required>
                <option value="">Başvuru Durumu Seçiniz</option>
                <option value="olumlu">Olumlu</option>
                <option value="olumsuz">Olumsuz</option>
            </select>

            <div id="conditionalFields" class="hidden">
                <label for="iletisim_tarihi_saat">İletişim Tarihi ve Saati:</label>
                <input type="datetime-local" id="iletisim_tarihi_saat" name="iletisim_tarihi_saat">

                <label for="mülakat_yeri">Mülakat Yeri:</label>
                <input type="text" id="mülakat_yeri" name="mülakat_yeri">
            </div>

            <div style="text-align: center; margin-top: 20px;">
                <button type="submit" class="submit-btn">Gönder</button>
            </div>
        </form>
        <div class="footer">
            <img src="https://i.hizliresim.com/bcb84v2.png" alt="Banner" class="banner"> 
            <p>© 2024 bcc İnsan Kaynakları Ekibi</p>
        </div>
    </div>

    <script>
        document.getElementById('ik-form').addEventListener('submit', function(event) {
            event.preventDefault();

            var isimInput = document.getElementById('isim');
            var soyisimInput = document.getElementById('soyisim');

            isimInput.value = capitalizeFirstLetter(isimInput.value);
            soyisimInput.value = capitalizeFirstLetter(soyisimInput.value);

            this.submit();
        });

        function capitalizeFirstLetter(string) {
            return string.charAt(0).toUpperCase() + string.slice(1).toLowerCase();
        }

        function toggleFields() {
            const yanitValue = document.getElementById('yanit').value;
            const conditionalFields = document.getElementById('conditionalFields');
            const iletisimTarihiSaat = document.getElementById('iletisim_tarihi_saat');
            const mulakatYeri = document.getElementById('mülakat_yeri');

            if (yanitValue === 'olumlu') {
                conditionalFields.classList.remove('hidden');
                iletisimTarihiSaat.setAttribute('required', true);
                mulakatYeri.setAttribute('required', true);
            } else {
                conditionalFields.classList.add('hidden');
                iletisimTarihiSaat.removeAttribute('required');
                mulakatYeri.removeAttribute('required');
            }
        }
    </script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
