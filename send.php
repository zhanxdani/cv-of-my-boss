<?php
// Файлы phpmailer
require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';
require 'phpmailer/Exception.php';
// Переменные, которые отправляет пользователь
$name = $_POST['name'];
$self = $_POST['self'];
$chest = $_POST['chest'];
$waist = $_POST['waist'];
$hips = $_POST['hips'];
$height = $_POST['height'];
$weight = $_POST['weight'];
$date = $_POST['date'];
$hair = $_POST['hair'];
$eyes = $_POST['eyes'];
$exp = $_POST['exp'];
$contact = $_POST['contact'];
$file = $_FILES['myfile'];

// Формирование самого письма
$title = "$name";
$body = "
<h2>Новое письмо</h2>
<b>Имя:</b> $name<br><br>
<b>О себе:</b> $self<br><br>
<b>Грудь,талия и бедра:</b> $chest-$waist-$hips<br><br>
<b>Рост:</b> $height<br><br>
<b>Вес:</b> $weight<br><br>
<b>Дата рождения:</b> $date<br><br>
<b>Волосы:</b> $hair<br><br>
<b>Глаза:</b> $eyes<br><br>
<b>Я:</b><br>$exp<br><br>
<b>Телефон:</b><br>$contact<br><br>";

// Настройки PHPMailer
$mail = new PHPMailer\PHPMailer\PHPMailer();
try {
    $mail->isSMTP();   
    $mail->CharSet = "UTF-8";
    $mail->SMTPAuth   = true;
    //$mail->SMTPDebug = 2;
    $mail->Debugoutput = function($str, $level) {$GLOBALS['status'][] = $str;};

    // Настройки вашей почты
    $mail->Host       = 'smtp.yandex.ru'; // SMTP сервера вашей почты
    $mail->Username   = 'avantomodels'; // Логин на почте
    $mail->Password   = 'hnlwlaspivkpgfox'; // Пароль на почте
    $mail->SMTPSecure = 'ssl';
    $mail->Port       = 465;
    $mail->setFrom('avantomodels@yandex.ru', 'Заявка с сайта!'); // Адрес самой почты и имя отправителя

    // Получатель письма
    $mail->addAddress('avantomodels@gmail.com');  

    // Прикрипление файлов к письму
if (!empty($file['name'][0])) {
    for ($ct = 0; $ct < count($file['tmp_name']); $ct++) {
        $uploadfile = tempnam(sys_get_temp_dir(), sha1($file['name'][$ct]));
        $filename = $file['name'][$ct];
        if (move_uploaded_file($file['tmp_name'][$ct], $uploadfile)) {
            $mail->addAttachment($uploadfile, $filename);
            $rfile[] = "Файл $filename прикреплён";
        } else {
            $rfile[] = "Не удалось прикрепить файл $filename";
        }
    }   
}

// Отправка сообщения
$mail->isHTML(true);
$mail->Subject = $title;
$mail->Body = $body;    

// Проверяем отравленность сообщения
if ($mail->send()) {
    $result = "success";           
}else {
    $result = "error";
    
}

} catch (Exception $e) {
    $result = "error";
    $status = "Сообщение не было отправлено. Причина ошибки: {$mail->ErrorInfo}";
}


 

// Отображение результата
echo json_encode(["result" => $result, "resultfile" => $rfile, "status" => $status]);
?>