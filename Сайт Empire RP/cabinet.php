<?php
require_once 'includes/auth.php';

if (!Auth::check()) {
    header("Location: login.php");
    exit;
}

$user = Auth::user();
?>

<!DOCTYPE html>

<html lang="ru">
<head>
<meta charset="UTF-8">
<title>Empire RP | Личный кабинет</title>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;700;800&display=swap" rel="stylesheet">

<style>

body {
    margin: 0;
    font-family: 'Inter', sans-serif;
    background: #0f1117;
    color: white;
}

.header {
    padding: 20px 8%;
    background: #151823;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.logo {
    font-size: 26px;
    font-weight: 800;
    color: #ffb300;
}

.logout a {
    color: white;
    text-decoration: none;
    background: #ffb300;
    padding: 10px 20px;
    border-radius: 10px;
    font-weight: 600;
}

.container {
    padding: 40px 8%;
}

.profile {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}

.card {
    background: #171a23;
    padding: 20px;
    border-radius: 15px;
}

.card h3 {
    margin: 0;
    margin-bottom: 10px;
    color: #ffb300;
}

.big {
    font-size: 22px;
    font-weight: 700;
}

.stats {
    margin-top: 30px;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
}

</style>

</head>
<body>

<div class="header">
    <div class="logo">Empire RP</div>
    <div class="logout">
        <a href="logout.php">Выйти</a>
    </div>
</div>

<div class="container">

<h2>Добро пожаловать, <?= htmlspecialchars($user['name']) ?></h2>

<div class="profile">

```
<div class="card">
    <h3>Уровень</h3>
    <div class="big"><?= $user['level'] ?></div>
</div>

<div class="card">
    <h3>Деньги</h3>
    <div class="big"><?= number_format($user['money'], 0, '.', ' ') ?> $</div>
</div>

<div class="card">
    <h3>Банк</h3>
    <div class="big"><?= number_format($user['bank'], 0, '.', ' ') ?> $</div>
</div>

<div class="card">
    <h3>Донат баланс</h3>
    <div class="big"><?= $user['donate_current'] ?></div>
</div>

<div class="card">
    <h3>Premium</h3>
    <div class="big"><?= $user['premium'] ? 'Активен' : 'Нет' ?></div>
</div>

<div class="card">
    <h3>Админ уровень</h3>
    <div class="big"><?= $user['admin'] ?></div>
</div>
```

</div>

<div class="stats">

```
<div class="card">
    <h3>Ник</h3>
    <div class="big"><?= $user['name'] ?></div>
</div>

<div class="card">
    <h3>Email</h3>
    <div class="big"><?= $user['email'] ?></div>
</div>

<div class="card">
    <h3>Последний вход</h3>
    <div class="big"><?= date("d.m.Y H:i", $user['last_login']) ?></div>
</div>

<div class="card">
    <h3>Регистрация</h3>
    <div class="big"><?= date("d.m.Y H:i", $user['reg_time']) ?></div>
</div>
```

</div>

</div>

</body>
</html>
