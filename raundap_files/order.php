<?php
// raundap_files/order.php

ini_set('display_errors', 1);
error_reporting(E_ALL);

// ===== TELEGRAM =====
$token  = '6897804929:AAE7BfzBRNefcjG5gpyx7KbLTmqf-13MwTY';
$chatId = '-4251179444';

// ===== Якщо відкрили напряму (GET) — просто назад на головну =====
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: /");
    exit;
}

// ===== Очистка =====
function clean($v) {
    return trim(strip_tags((string)$v));
}

// ===== Дані з форми =====
$name  = clean($_POST['name'] ?? '');
$phone = clean($_POST['phone'] ?? '');
$size  = clean($_POST['size'] ?? '');

if ($name === '' || $phone === '' || $size === '') {
    header("Location: /?err=1");
    exit;
}

// ===== Додатково =====
$ip   = $_SERVER['REMOTE_ADDR'] ?? '-';
$page = $_SERVER['HTTP_REFERER'] ?? '-';

// ===== Повідомлення =====
$message =
"🛒 НОВЕ ЗАМОВЛЕННЯ (Раундап)\n\n" .
"👤 Ім'я: {$name}\n" .
"📞 Телефон: {$phone}\n" .
"📦 Літраж: {$size}\n" .
"🌐 Сторінка: {$page}\n" .
"📍 IP: {$ip}";

// ===== Відправка в Telegram =====
$url = "https://api.telegram.org/bot{$token}/sendMessage";

$data = [
    'chat_id' => $chatId,
    'text' => $message,
    'disable_web_page_preview' => true
];

// Надійний POST через file_get_contents
$options = [
    'http' => [
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data),
        'timeout' => 15
    ]
];

$context  = stream_context_create($options);
$response = @file_get_contents($url, false, $context);

// Якщо Telegram не відповів — покажемо помилку (щоб ти бачив)
if ($response === false) {
    http_response_code(500);
    exit('Помилка: не вдалося відправити в Telegram (перевір токен/доступ/хостинг)');
}

// ===== Редірект на сторінку "дякую" =====
header("Location: /thanks.html");
exit;
