<?php
// raundap_files/order.php

ini_set('display_errors', 1);
error_reporting(E_ALL);

// ====== TELEGRAM ======
$token  = '6897804929:AAE7BfzBRNefcjG5gpyx7KbLTmqf-13MwTY';
$chatId = '-4251179444'; // якщо це група і не працює — треба буде взяти правильний через getUpdates

// ====== Перевірка методу ======
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method Not Allowed');
}

// ====== Очистка ======
function clean($v) {
    return trim(strip_tags((string)$v));
}

// ====== Дані з форми (ВАЖЛИВО: саме name/phone/size) ======
$name  = clean($_POST['name'] ?? '');
$phone = clean($_POST['phone'] ?? '');
$size  = clean($_POST['size'] ?? '');

if ($name === '' || $phone === '' || $size === '') {
    http_response_code(400);
    exit('Заповніть усі поля');
}

// Додатково
$page = $_SERVER['HTTP_REFERER'] ?? '—';
$ip   = $_SERVER['REMOTE_ADDR'] ?? '—';

// ====== Повідомлення ======
$message =
"🛒 НОВЕ ЗАМОВЛЕННЯ (Раундап)\n\n" .
"👤 Ім'я: {$name}\n" .
"📞 Телефон: {$phone}\n" .
"📦 Літраж: {$size}\n\n" .
"🌐 Сторінка: {$page}\n" .
"📍 IP: {$ip}";

// ====== Відправка в Telegram через cURL ======
$url = "https://api.telegram.org/bot{$token}/sendMessage";

$postData = [
    'chat_id' => $chatId,
    'text' => $message,
    'disable_web_page_preview' => true
];

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
curl_setopt($ch, CURLOPT_TIMEOUT, 15);

$response = curl_exec($ch);
$curlErr  = curl_error($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($response === false) {
    http_response_code(500);
    exit("cURL помилка: {$curlErr}");
}

if ($httpCode !== 200) {
    http_response_code(500);
    exit("Telegram API HTTP {$httpCode}. Response: {$response}");
}

// ====== Успіх: редірект назад на сторінку ======
$back = $_SERVER['HTTP_REFERER'] ?? '/';
$sep  = (strpos($back, '?') !== false) ? '&' : '?';
header("Location: {$back}{$sep}success=1");
exit;