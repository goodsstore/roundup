<?php

$token = "8798222326:AAFgErWlAjySAoKWhrbZ4obmuTpJ3kPiDxQ";
$chat_id = "-5225515433";

$name = htmlspecialchars($_POST['name'] ?? '');
$phone = htmlspecialchars($_POST['phone'] ?? '');
$weight = htmlspecialchars($_POST['weight'] ?? '');

if (!$name  !$phone  !$weight) {
    echo "Заповніть всі поля";
    exit;
}

$message = "🛒 НОВЕ ЗАМОВЛЕННЯ\n\n";
$message .= "👤 Ім'я: $name\n";
$message .= "📞 Телефон: $phone\n";
$message .= "📦 Обʼєм: $weight\n";

$url = "https://api.telegram.org/bot{$token}/sendMessage";

$data = [
    'chat_id' => $chat_id,
    'text' => $message
];

$options = [
    'http' => [
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data),
    ],
];

$context = stream_context_create($options);
file_get_contents($url, false, $context);

// Відповідь користувачу
echo "Дякуємо! Заявка відправлена.";
