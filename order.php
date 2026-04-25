<?php

$token = "8798222326:AAFgErWlAjySAoKWhrbZ4obmuTpJ3kPiDxQ";
$chat_id = "-5225515433";

// Отримання і очищення даних
$name = htmlspecialchars($_POST['name'] ?? '');
$phone = htmlspecialchars($_POST['phone'] ?? '');
$weight = htmlspecialchars($_POST['weight'] ?? '');

// Перевірка
if (!$name || !$phone || !$weight) {
    echo "Заповніть всі поля";
    exit;
}

// Формування повідомлення
$message = "🛒 НОВЕ ЗАМОВЛЕННЯ\n\n";
$message .= "👤 Ім'я: $name\n";
$message .= "📞 Телефон: $phone\n";
$message .= "📦 Обʼєм: $weight\n";

// URL Telegram API
$url = "https://api.telegram.org/bot{$token}/sendMessage";

// Дані для відправки
$data = [
    'chat_id' => $chat_id,
    'text' => $message,
    'parse_mode' => 'HTML'
];

// Відправка через cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);

// Перевірка помилки cURL
if (curl_errno($ch)) {
    echo "Помилка cURL: " . curl_error($ch);
    curl_close($ch);
    exit;
}

curl_close($ch);

// Декодуємо відповідь Telegram
$result = json_decode($response, true);

// Перевірка відповіді Telegram
if (!$result['ok']) {
    echo "Помилка Telegram: " . $result['description'];
    exit;
}

// Успіх
echo "Дякуємо! Заявка відправлена.";
