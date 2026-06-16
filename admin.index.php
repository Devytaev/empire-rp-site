<?php
require_once '../includes/admin_auth.php';
require_once '../includes/database.php';

$db = Database::connect();

$users = $db->query("SELECT COUNT(*) as c FROM accounts")->fetch_assoc();
$online = $db->query("SELECT COUNT(*) as c FROM accounts WHERE last_login > UNIX_TIMESTAMP()-3600")->fetch_assoc();
$donate = $db->query("SELECT SUM(donate_current) as c FROM accounts")->fetch_assoc();
?>

<!DOCTYPE html>

<html lang="ru">
<head>
<meta charset="UTF-8">
<title>Empire RP Admin</title>

<style>

body {
    margin: 0;
    font-family: Arial;
    background: #0f1117;
    color: white;
}

.sidebar {
    width: 250px;
    height: 100vh;
    position: fixed;
    background: #151823;
    padding: 20px;
}

.sidebar h2 {
    color: #ffb300;
}

.sidebar a {
    display: block;
    color: white;
    text-decoration: none;
    margin: 15px 0;
}

.main {
    margin-left: 270px;
    padding: 30px;
}

.card {
    background: #171a23;
    padding: 20px;
    border-radius: 10px;
    margin-bottom: 20px;
}

.grid {
    display: grid;
    grid-template-columns: repeat(auto-fit,minmax(200px,1fr));
    gap: 20px;
}

.big {
    font-size: 26px;
    color: #ffb300;
}

</style>

</head>

<body>

<div class="sidebar">
    <h2>Empire Admin</h2>
    <a href="index.php">📊 Дашборд</a>
    <a href="users.php">👤 Игроки</a>
    <a href="news.php">📰 Новости</a>
    <a href="donate.php">💰 Донат логи</a>
    <a href="../index.php">🏠 Сайт</a>
</div>

<div class="main">

<h1>📊 Панель управления</h1>

<div class="grid">

<div class="card">
    <div>Игроки</div>
    <div class="big"><?= $users['c'] ?></div>
</div>

<div class="card">
    <div>Онлайн (1ч)</div>
    <div class="big"><?= $online['c'] ?></div>
</div>

<div class="card">
    <div>Всего доната</div>
    <div class="big"><?= $donate['c'] ?></div>
</div>

</div>

</div>

</body>
</html>
