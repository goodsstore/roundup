<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Отримуємо дані з форми
$name = $_POST['name'];
$phone = $_POST['phone'];
$weight = $_POST['weight'];

$token = "8798222326:AAFgErWlAjySAoKWhrbZ4obmuTpJ3kPiDxQ"; // обовʼязково новий!
$chat_id = "-5225515433";

// Формуємо повідомлення
$txt = "🛒 НОВЕ ЗАМОВЛЕННЯ%0A%0A";
$txt .= "👤 Імʼя: ".$name."%0A";
$txt .= "📞 Телефон: ".$phone."%0A";
$txt .= "📦 Обʼєм: ".$weight."%0A";

// Відправка
$url = "https://api.telegram.org/bot".$token."/sendMessage?chat_id=".$chat_id."&text=".$txt;

$result = file_get_contents($url);

// Перевірка
if ($result) {
    header("Location: zakaz.html");
} else {
    echo "Помилка відправки";
}
?>