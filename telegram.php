<?php

/* https://api.telegram.org/bot7492982602:AAGci6YXKW64JM6fdm6ud_55hYX7sAJl-PI/getUpdates,
где, XXXXXXXXXXXXXXXXXXXXXXX - токен вашего бота, полученный ранее 
*/


$name = $_POST['name'];
$name = $_POST['product_data'];
$phone = $_POST['phone'];
$token = "7492982602:AAGci6YXKW64JM6fdm6ud_55hYX7sAJl-PI";
$chat_id = "-4223623903";
$arr = array(
  'Оберіть товар: ' => $product_data,
  'Имя пользователя: ' => $name,
  'Телефон: ' => $phone,
);

foreach($arr as $key => $value) {
  $txt .= "<b>".$key."</b> ".$value."%0A";
};

$sendToTelegram = fopen("https://api.telegram.org/bot{$token}/sendMessage?chat_id={$chat_id}&parse_mode=html&text={$txt}","r");

if ($sendToTelegram) {
  header('Location: zakaz.html');
} else {
  echo "Error";
}
?>

