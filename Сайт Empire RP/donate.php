<?php
require_once 'includes/auth.php';

if (!Auth::check()) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<title>Empire RP | Донат</title>

<style>
body {background:#0f1117;color:white;font-family:Arial;}
.container {padding:40px;}
.card {background:#171a23;padding:20px;margin:10px;border-radius:10px;}
button {padding:10px 20px;background:#ffb300;border:none;cursor:pointer;}
</style>
</head>

<body>

<div class="container">

<h1>💰 Донат-магазин Empire RP</h1>

<div class="card">
    <h3>VIP 30 дней</h3>
    <p>299 ₽</p>
    <a href="donate_buy.php?item=vip"><button>Купить</button></a>
</div>

<div class="card">
    <h3>+500.000$</h3>
    <p>99 ₽</p>
    <a href="donate_buy.php?item=money"><button>Купить</button></a>
</div>

<div class="card">
    <h3>+100 Donate</h3>
    <p>49 ₽</p>
    <a href="donate_buy.php?item=donate"><button>Купить</button></a>
</div>

</div>

</body>
</html>