<?php
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$status = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Veritabanı bağlantısı ayarları
    $dsn = 'mysql:host=localhost;dbname=email_logs_db;charset=utf8';
    $username = 'root'; 
    $password = ''; 
    try {
        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Veritabanı bağlantısı başarısız oldu: " . htmlspecialchars($e->getMessage()));
    }

    $isim = htmlspecialchars(trim($_POST['isim'] ?? ''));
    $soyisim = htmlspecialchars(trim($_POST['soyisim'] ?? ''));
    $epostaAdresi = filter_var(trim($_POST['eposta'] ?? ''), FILTER_SANITIZE_EMAIL);
    $yanit = htmlspecialchars(trim($_POST['yanit'] ?? ''));
    $sirket = htmlspecialchars(trim($_POST['sirket'] ?? ''));
    $pozisyon = htmlspecialchars(trim($_POST['pozisyon'] ?? ''));
    $iletisimTarihi = htmlspecialchars(trim($_POST['iletisim_tarihi_saat'] ?? ''));
    $mülakatYeri = htmlspecialchars(trim($_POST['mülakat_yeri'] ?? ''));

    if (!filter_var($epostaAdresi, FILTER_VALIDATE_EMAIL)) {
        die("Geçersiz e-posta adresi.");
    }

    $isim = ucwords(strtolower($isim));
    $soyisim = ucwords(strtolower($soyisim));

    if ($yanit === 'olumlu' && empty($iletisimTarihi)) {
        die("İletişim tarihi girilmedi.");
    }

    $table = $yanit === 'olumlu' ? 'positive_responses' : 'negative_responses';
    $otherTable = $yanit === 'olumlu' ? 'negative_responses' : 'positive_responses';

    $params = [
        ':email' => $epostaAdresi,
        ':company' => $sirket,
        ':interview_location' => $mülakatYeri
    ];
    if ($yanit === 'olumlu') {
        $query = "SELECT * FROM $table WHERE email = :email AND company = :company AND interview_location = :interview_location AND contact_date = :contact_date";
        $params[':contact_date'] = $iletisimTarihi;
    } else {
        $query = "SELECT * FROM $table WHERE email = :email AND company = :company AND interview_location = :interview_location";
    }

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $existingRecord = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existingRecord) {
        $status = $yanit === 'olumlu' ? 'repeated_positive' : 'repeated_negative';
    } else {
        $paramsOtherTable = $params;
        if ($yanit === 'olumlu') {
            $queryOtherTable = "SELECT * FROM $otherTable WHERE email = :email AND company = :company AND interview_location = :interview_location AND contact_date = :contact_date";
            $paramsOtherTable[':contact_date'] = $iletisimTarihi;
        } else {
            $queryOtherTable = "SELECT * FROM $otherTable WHERE email = :email AND company = :company AND interview_location = :interview_location";
        }

        $stmt = $pdo->prepare($queryOtherTable);
        $stmt->execute($paramsOtherTable);
        $existingOtherRecord = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existingOtherRecord) {
            $status = $yanit === 'olumlu' ? 'positive_attempted_as_negative' : 'negative_attempted_as_positive';
        } else {
            try {
                $queryInsert = "INSERT INTO $table (email, company, interview_location, status, first_name, last_name, position";
                $values = ":email, :company, :interview_location, 'beklemede', :first_name, :last_name, :position";
                if ($yanit === 'olumlu') {
                    $queryInsert .= ", contact_date";
                    $values .= ", :contact_date";
                }
                $queryInsert .= ") VALUES ($values)";
                
                $stmt = $pdo->prepare($queryInsert);
                $paramsInsert = $params;
                $paramsInsert[':first_name'] = $isim;
                $paramsInsert[':last_name'] = $soyisim;
                $paramsInsert[':position'] = $pozisyon;
                if ($yanit === 'olumlu') {
                    $paramsInsert[':contact_date'] = $iletisimTarihi;
                }
                $stmt->execute($paramsInsert);

                $mail = new PHPMailer(true);
                $mail->isSMTP();
                $mail->Host = 'smtp.office365.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'vcfdsa323@outlook.com'; 
                $mail->Password = '7K2_2qdenem'; 
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;
                $mail->CharSet = 'UTF-8';
                $mail->setFrom('vcfdsa323@outlook.com', 'bcc İnsan Kaynakları Ekibi');
                $mail->addAddress($epostaAdresi);
                $mail->Subject = $yanit === 'olumlu' ? 'Başvurunuz Hakkında' : 'Başvurunuz Hakkında';

                $formattedDate = $yanit === 'olumlu' ? date('d-m-Y', strtotime($iletisimTarihi)) : '';
                $formattedTime = $yanit === 'olumlu' ? date('H:i', strtotime($iletisimTarihi)) : '';
                $mail->isHTML(true);
                $mail->Body = $yanit === 'olumlu' ? "
                <!DOCTYPE html>
            <html lang=\"tr\">
            <head>
                <meta charset=\"UTF-8\">
                <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
                <title>Başvurunuz Hakkında</title>
                <style>
                    body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; color: #333333; }
                    .container { max-width: 600px; margin: 50px auto; background-color: #ffffff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); }
                    .header { text-align: center; padding-bottom: 20px; }
                    .banner { width: 100%; height: 200px; object-fit: cover; border-bottom: 1px solid #dddddd; }
                    .content { padding: 20px 0; }
                    .content h2 { color: #0073e6; margin: 0 0 10px 0; }
                    .content p { margin: 0 0 15px 0; line-height: 1.6; }
                    .footer { text-align: center; padding-top: 20px; border-top: 1px solid #dddddd; font-size: 12px; color: #666666; }
                    .footer p { margin: 5px 0; }
                    .footer .banner { width: 150px; height: auto; }
                </style>
            </head>
            <body>
                <div class=\"container\">
                    <div class=\"header\">
                        <img src=\"https://i.hizliresim.com/sd6voeg.png\" alt=\"Banner\" class=\"banner\">
                    </div>
                    <div class=\"content\">
                        <p>Sevgili <strong>$isim $soyisim</strong>,</p>
                        <p><strong>$sirket</strong>, $pozisyon pozisyonu mülakatına katıldığınız için teşekkür ederiz.</p>
                        <p>Önceki deneyimleriniz ve role yönelik isteğiniz bizi etkiledi. Sizi son mülakata davet etmekten mutluluk duyuyoruz.</p>
                        <p>Başvurunuz olumlu değerlendirilmiştir. Mülakat tarih: <strong>$formattedDate ve saati: $formattedTime</strong> ve mülakat yeri: <strong style=\"text-transform: uppercase;\">$mülakatYeri</strong></p>
                        <p>Bu görüşmeye katılmak istiyorsanız lütfen yazılı olarak onay verin.</p>
                        <p>Rol veya işe alım süreci hakkında sorularınız varsa, lütfen iletişime geçin.</p>
                    </div>
                    <div class=\"footer\">
                        <p>Saygılarımızla,</p>
                        <p>bcc insan kaynakları Ekibi</p>
                        <img src=\"https://i.hizliresim.com/bcb84v2.png\" alt=\"Footer Logo\" class=\"banner\">
                    </div>
                </div>
            </body>
            </html>
        " : "
            <!DOCTYPE html>
            <html lang=\"tr\">
            <head>
                <meta charset=\"UTF-8\">
                <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
                <title>Başvurunuz Hakkında</title>
                <style>
                    body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; color: #333333; }
                    .container { max-width: 600px; margin: 50px auto; background-color: #ffffff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); }
                    .header { text-align: center; padding-bottom: 20px; }
                    .banner { width: 100%; height: 200px; object-fit: cover; border-bottom: 1px solid #dddddd; }
                    .content { padding: 20px 0; }
                    .content h2 { color: #0073e6; margin: 0 0 10px 0; }
                    .content p { margin: 0 0 15px 0; line-height: 1.6; }
                    .footer { text-align: center; padding-top: 20px; border-top: 1px solid #dddddd; font-size: 12px; color: #666666; }
                    .footer p { margin: 5px 0; }
                    .footer .banner { width: 150px; height: auto; }
                </style>
            </head>
            <body>
                <div class=\"container\">
                    <div class=\"header\">
                        <img src=\"https://i.hizliresim.com/sd6voeg.png\" alt=\"Banner\" class=\"banner\">
                    </div>
                    <div class=\"content\">
                        <p>Sevgili <strong>$isim $soyisim</strong>,</p>
                    <p><strong>$sirket</strong>, $pozisyon pozisyonu için mülakata katıldığınız için teşekkür ederiz.</p>
                    <p>Yapılan değerlendirmeler sonucunda bu pozisyon için uygun bulunmadınız.</p>
                    <p>Başvurunuza olan ilginiz için teşekkür eder, gelecekteki fırsatlarda sizi tekrar görmek isteriz.</p>
                    <p>Başka bir pozisyon için başvuruda bulunmak isterseniz, size uygun fırsatları değerlendirmekten mutluluk duyarız.</p>
                    <p>Başarılar dileriz.</p>
                    </div>
                    <div class=\"footer\">
                        <p>Saygılarımızla,</p>
                        <p>bcc insan kaynakları Ekibi</p>
                        <img src=\"https://i.hizliresim.com/bcb84v2.png\" alt=\"Footer Logo\" class=\"banner\">
                    </div>
                </div>
            </body>
            </html>
            ";
                $mail->send();
                $status = $yanit === 'olumlu' ? 'positive_success' : 'negative_success';
            } catch (Exception $e) {
                $status = $yanit === 'olumlu' ? 'positive_error' : 'negative_error';
            }
        }
    }

    echo json_encode(['status' => $status]);
}
?>


<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Durum Mesajı</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>
<body>
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
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Gönderimi</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <form id="myForm" action="send_email.php" method="post">
        <input type="text" name="isim" placeholder="İsim" required>
        <input type="text" name="soyisim" placeholder="Soyisim" required>
        <input type="email" name="eposta" placeholder="E-posta" required>
        <select name="yanit">
            <option value="olumlu">Olumlu</option>
            <option value="olumsuz">Olumsuz</option>
        </select>
        <button type="submit">Gönder</button>
    </form>

    <script>
    document.getElementById('myForm').addEventListener('submit', function(event) {
        event.preventDefault(); 

        let formData = new FormData(this);

        fetch('send_email.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            let title, text;
            switch(data.status) {
                case 'positive_success':
                    title = 'Başarı';
                    text = 'E-posta başarılı bir şekilde gönderildi.';
                    break;
                case 'negative_success':
                    title = 'Başarı';
                    text = 'E-posta başarılı bir şekilde gönderildi.';
                    break;
                case 'positive_error':
                    title = 'Hata';
                    text = 'E-posta gönderiminde bir hata oluştu.';
                    break;
                case 'negative_error':
                    title = 'Hata';
                    text = 'E-posta gönderiminde bir hata oluştu.';
                    break;
                case 'repeated_positive':
                    title = 'Bilgi';
                    text = 'Bu e-posta için olumlu bir yanıt zaten mevcut.';
                    break;
                case 'repeated_negative':
                    title = 'Bilgi';
                    text = 'Bu e-posta için olumsuz bir yanıt zaten mevcut.';
                    break;
                case 'positive_attempted_as_negative':
                    title = 'Bilgi';
                    text = 'Bu e-posta için olumlu bir yanıt mevcut, ancak olumsuz olarak tekrar denendi.';
                    break;
                case 'negative_attempted_as_positive':
                    title = 'Bilgi';
                    text = 'Bu e-posta için olumsuz bir yanıt mevcut, ancak olumlu olarak tekrar denendi.';
                    break;
                default:
                    title = 'Hata';
                    text = 'Bilinmeyen bir durum oluştu.';
            }
            Swal.fire({
                title: title,
                text: text,
                icon: data.status.includes('error') ? 'error' : 'success'
            });
        })
        .catch(error => {
            Swal.fire({
                title: 'Hata',
                text: 'Sunucu ile iletişimde bir hata oluştu.',
                icon: 'error'
            });
        });
    });
    </script>
</body>
</html>
