<?php
require_once 'includes/database.php';
require_once 'includes/freekassa_config.php';

$db = Database::connect();

// данные от FreeKassa
$merchant_id = $_REQUEST['MERCHANT_ID'];
$amount      = $_REQUEST['AMOUNT'];
$order_id    = $_REQUEST['MERCHANT_ORDER_ID'];
$sign        = $_REQUEST['SIGN'];

// проверка подписи (ЗАЩИТА №1)
$my_sign = md5($merchant_id . ":" . $amount . ":" . FK_SECRET_2 . ":" . $order_id);

if ($sign != $my_sign) {
    die("bad sign");
}

// проверяем заказ
$stmt = $db->prepare("SELECT * FROM site_payments WHERE order_id = ?");
$stmt->bind_param("s", $order_id);
$stmt->execute();

$pay = $stmt->get_result()->fetch_assoc();

if (!$pay) {
    die("order not found");
}

// защита от повторной оплаты
if ($pay['status'] == 'paid') {
    die("already paid");
}

// получаем юзера
$user_id = $pay['user_id'];
$user = $db->query("SELECT * FROM accounts WHERE id = $user_id")->fetch_assoc();

if (!$user) {
    die("user not found");
}

// определяем товар из order_id
$parts = explode("_", $order_id);
$item = $parts[2] ?? '';

// ===== ВЫДАЧА =====
if ($item == "vip") {
    $db->query("
        UPDATE accounts 
        SET premium = 1,
            premium_time = UNIX_TIMESTAMP() + 2592000
        WHERE id = $user_id
    ");
}

if ($item == "money") {
    $db->query("
        UPDATE accounts 
        SET money = money + 500000
        WHERE id = $user_id
    ");
}

if ($item == "donate") {
    $db->query("
        UPDATE accounts 
        SET donate_current = donate_current + 100
        WHERE id = $user_id
    ");
}

// обновляем оплату (ЗАЩИТА №2)
$db->query("
    UPDATE site_payments 
    SET status = 'paid', paid_at = UNIX_TIMESTAMP()
    WHERE order_id = '$order_id'
");

// лог
$db->query("
    INSERT INTO site_donate_logs (user_id, nickname, item, amount, created_at)
    VALUES ($user_id, '{$user['name']}', '$item', $amount, UNIX_TIMESTAMP())
");

echo "YES";