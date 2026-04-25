<?php
session_start();
date_default_timezone_set('Europe/Kyiv');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit;
}

/* ==========================
   TELEGRAM
========================== */

$tg_token  = '6897804929:AAExjY2UeQjEO9fjDZfMOKB7DS7MwXef_jw';
$tg_chatid = '-4251179444';

/* ==========================
   ДАНІ
========================== */

$name   = trim($_POST['name'] ?? '');
$phone  = preg_replace('/[^0-9]/', '', $_POST['phone'] ?? '');
$weight = trim($_POST['weight'] ?? '');

$utm_source   = trim($_POST['utm_source'] ?? '');
$utm_medium   = trim($_POST['utm_medium'] ?? '');
$utm_campaign = trim($_POST['utm_campaign'] ?? '');
$utm_term     = trim($_POST['utm_term'] ?? '');
$utm_content  = trim($_POST['utm_content'] ?? '');

$product_title = 'Раундап';

/* ==========================
   SEND
========================== */

if (!empty($phone)) {

    $site = $_SERVER['SERVER_NAME'];

    $text  = "🌿 <b>Нове замовлення — {$product_title}</b>\n\n";
    $text .= "<b>Ім'я:</b> {$name}\n";
    $text .= "<b>Телефон:</b> {$phone}\n";
    $text .= "<b>Вага:</b> {$weight}\n\n";

    if (
        $utm_source ||
        $utm_medium ||
        $utm_campaign ||
        $utm_term ||
        $utm_content
    ) {
        $text .= "📊 <b>UTM дані:</b>\n";

        if ($utm_source)   $text .= "<b>Source:</b> {$utm_source}\n";
        if ($utm_medium)   $text .= "<b>Medium:</b> {$utm_medium}\n";
        if ($utm_campaign) $text .= "<b>Campaign:</b> {$utm_campaign}\n";
        if ($utm_term)     $text .= "<b>Term:</b> {$utm_term}\n";
        if ($utm_content)  $text .= "<b>Content:</b> {$utm_content}\n";

        $text .= "\n";
    }

    $text .= "🌐 <b>Сайт:</b> {$site}\n";
    $text .= "🕒 <b>Дата:</b> " . date("Y-m-d H:i:s");

    $url = "https://api.telegram.org/bot{$tg_token}/sendMessage";

    $data = [
        'chat_id' => $tg_chatid,
        'text' => $text,
        'parse_mode' => 'HTML'
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

    $response = curl_exec($ch);
    curl_close($ch);
}
?>