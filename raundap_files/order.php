
<?php
echo '<pre>';
print_r($_SERVER['REQUEST_METHOD']);
print_r($_POST);
exit;

<?php
// raundap_files/order.php

ini_set('display_errors', 1);
error_reporting(E_ALL);

// ===== TELEGRAM =====
$token  = '6897804929:AAE7BfzBRNefcjG5gpyx7KbLTmqf-13MwTY';
$chatId = '-4251179444';

// ===== Перевірка POST =====
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit('Method Not Allowed');
}

// ===== Очистка =====
function clean($v) {
    return trim(strip_tags($v));
}

// ===== Дані з форми =====
$name  = clean($_POST['name'] ?? '');
$phone = clean($_POST['phone'] ?? '');
$size  = clean($_POST['size'] ?? '');

if ($name == '' || $phone == '' || $size == '') {
    exit('Заповніть всі поля');
}

// ===== Додатково =====
$ip = $_SERVER['REMOTE_ADDR'] ?? '-';

// ===== Повідомлення =====
$message = "🛒 НОВЕ ЗАМОВЛЕННЯ\n\n";
$message .= "👤 Ім'я: $name\n";
$message .= "📞 Телефон: $phone\n";
$message .= "📦 Літраж: $size\n";
$message .= "🌐 IP: $ip";

// ===== Відправка в Telegram =====
$url = "https://api.telegram.org/bot$token/sendMessage";

$data = [
    'chat_id' => $chatId,
    'text' => $message
];

$options = [
    'http' => [
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data)
    ]
];

$context = stream_context_create($options);
file_get_contents($url, false, $context);

// ===== Редірект на сторінку дякую =====
header("Location: /thanks.html");
exit;

