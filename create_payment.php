<?php
require_once 'includes/auth.php';
require_once 'includes/freekassa_config.php';
require_once 'includes/database.php';

if (!Auth::check()) {
    die("not auth");
}

$db = Database::connect();
$user = Auth::user();

$item = $_GET['item'] ?? '';

// цены
$prices = [
    'vip' => 299,
    'money' => 99,
    'donate' => 49
];

if (!isset($prices[$item])) {
    die("wrong item");
}

$amount = $prices[$item];

// уникальный заказ
$order_id = time() . "_" . $user['id'] . "_" . $item;

// записываем в БД (защита от повторной оплаты)
$stmt = $db->prepare("
    INSERT INTO site_payments (user_id, order_id, amount, created_at)
    VALUES (?, ?, ?, UNIX_TIMESTAMP())
");
$stmt->bind_param("isi", $user['id'], $order_id, $amount);
$stmt->execute();

// подпись FreeKassa
$sign = md5(FK_SHOP_ID . ":" . $amount . ":" . FK_SECRET_1 . ":" . $order_id);

$url = "https://pay.freekassa.com/?m=" . FK_SHOP_ID .
"&oa=" . $amount .
"&o=" . $order_id .
"&s=" . $sign .
"&i=RUB";

header("Location: " . $url);
exit;