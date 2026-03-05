<?php
session_start();
date_default_timezone_set('Europe/Kyiv');

/* ==========================
   TELEGRAM
========================== */
$tg_token  = '6897804929:AAE7BfzBRNefcjG5gpyx7KbLTmqf-13MwTY';
$tg_chatid = '-4251179444';

/* ==========================
   ДАНІ З ФОРМИ
========================== */
$name  = trim($_POST['name'] ?? '');
$phone = preg_replace('/[^0-9]/', '', $_POST['phone'] ?? '');

// ✅ у формі select називається size (літраж)
$size  = trim($_POST['size'] ?? '');

$utm_source   = trim($_POST['utm_source'] ?? '');
$utm_medium   = trim($_POST['utm_medium'] ?? '');
$utm_campaign = trim($_POST['utm_campaign'] ?? '');
$utm_term     = trim($_POST['utm_term'] ?? '');
$utm_content  = trim($_POST['utm_content'] ?? '');

$product_title = 'Раундап';

/* ==========================
   ВІДПРАВКА В TELEGRAM
========================== */
$tg_sent = false;

if (!empty($phone)) {

    $site = $_SERVER['SERVER_NAME'] ?? '';

    $text  = "🛒 <b>Нове замовлення — {$product_title}</b>\n\n";
    $text .= "<b>Ім'я:</b> {$name}\n";
    $text .= "<b>Телефон:</b> {$phone}\n";
    $text .= "<b>Літраж:</b> {$size}\n\n";

    // ✅ UTM додаємо тільки якщо хоч одне є
    if ($utm_source || $utm_medium || $utm_campaign || $utm_term || $utm_content) {
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

    // Відправка через cURL (POST)
    $url = "https://api.telegram.org/bot{$tg_token}/sendMessage";

    $postData = [
        'chat_id' => $tg_chatid,
        'text' => $text,
        'parse_mode' => 'HTML',
        'disable_web_page_preview' => true
    ];

    if (function_exists('curl_init')) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        $res = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($code === 200 && $res) $tg_sent = true;
    }
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
<meta charset="UTF-8">
<title>Дякуємо за замовлення</title>
<style>
body{font-family:Arial;text-align:center;background:#f4f6f9;padding-top:80px;margin:0}
.box{background:#fff;max-width:450px;margin:auto;padding:30px;border-radius:10px;box-shadow:0 0 20px rgba(0,0,0,.1)}
h1{color:#2e7d32;margin-top:0}
.btn{display:inline-block;margin-top:20px;padding:12px 25px;background:#2e7d32;color:#fff;text-decoration:none;border-radius:6px}
.note{font-size:12px;color:#666;margin-top:10px}
</style>

<!-- Meta Pixel Code -->
<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window, document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '1616320739709749');
fbq('track', 'Lead');
</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=1616320739709749&ev=PageView&noscript=1"
/></noscript>
<!-- End Meta Pixel Code -->

</head>
<body>

<div class="box">
  <h1>Дякуємо за замовлення! ✅</h1>
  <p>Наш менеджер скоро вам зателефонує.</p>

  <p><b>Ім'я:</b> <?= htmlspecialchars($name) ?></p>
  <p><b>Телефон:</b> <?= htmlspecialchars($phone) ?></p>
  <p><b>Літраж:</b> <?= htmlspecialchars($size) ?></p>

  <a href="/" class="btn">Повернутися на сайт</a>

  <?php if (!$tg_sent): ?>
    <div class="note">Примітка: Telegram може не прийняти повідомлення, якщо chat_id неправильний або бот не має доступу до групи.</div>
  <?php endif; ?>
</div>

</body>
</html>