<?php
require_once 'includes/database.php';
session_start();

$db = Database::connect();

/*
  ⚠️ ОНЛАЙН СЕРВЕРА
  Пока сделано через заглушку.
  Потом можно подключить samp-query API или JSON с игрового сервера.
*/
$serverOnline = rand(120, 450); // временно

// ТОП игроков
$top = $db->query("
    SELECT name, level, money
    FROM accounts
    ORDER BY level DESC
    LIMIT 5
");

// НОВОСТИ (нужна таблица site_news)
$news = $db->query("
    SELECT title, content, created_at
    FROM site_news
    ORDER BY id DESC
    LIMIT 3
");
?>

<!DOCTYPE html>

<html lang="ru">
<head>
<meta charset="UTF-8">
<title>Empire RP | Главная</title>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&display=swap" rel="stylesheet">

<style>

body {
    margin: 0;
    font-family: Inter;
    background: #0f1117;
    color: white;
}

/* HEADER */
.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 8%;
    background: rgba(21,24,35,0.9);
    backdrop-filter: blur(10px);
    position: fixed;
    width: 100%;
    top: 0;
}

.logo {
    font-size: 26px;
    font-weight: 800;
    color: #ffb300;
}

.nav a {
    color: white;
    text-decoration: none;
    margin-left: 20px;
    font-weight: 500;
}

.nav a:hover {
    color: #ffb300;
}

/* HERO */
.hero {
    height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.8)),
                url('https://images.unsplash.com/photo-1605902711622-cfb43c4437d1');
    background-size: cover;
    background-position: center;
}

.hero h1 {
    font-size: 70px;
    margin-bottom: 10px;
}

.hero span {
    color: #ffb300;
}

.btn {
    margin-top: 20px;
    padding: 15px 40px;
    background: #ffb300;
    border: none;
    font-size: 18px;
    font-weight: 700;
    border-radius: 10px;
    cursor: pointer;
}

/* BLOCKS */
.section {
    padding: 80px 8%;
}

.grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 20px;
}

.card {
    background: #171a23;
    padding: 20px;
    border-radius: 15px;
}

/* TOP PLAYERS */
.rank {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
    border-bottom: 1px solid #2a2d3a;
}

/* NEWS */
.news-title {
    color: #ffb300;
    font-weight: 700;
}

footer {
    text-align: center;
    padding: 40px;
    background: #090b10;
}

.online {
    font-size: 20px;
    color: #00ff88;
    margin-top: 10px;
}

</style>

</head>

<body>

<!-- HEADER -->

<div class="header">
    <div class="logo">Empire RP</div>

```
<div class="nav">
    <a href="index.php">Главная</a>
    <a href="cabinet.php">Кабинет</a>
    <a href="donate.php">Донат</a>
    <a href="forum.php">Форум</a>
    <a href="login.php">Войти</a>
</div>
```

</div>

<!-- HERO -->

<div class="hero">
    <div>
        <h1>Добро пожаловать в <span>Empire RP</span></h1>
        <p>Лучший SA:MP RolePlay проект нового поколения</p>

```
    <div class="online">
        Онлайн сервера: <?= $serverOnline ?> игроков
    </div>

    <button class="btn" onclick="window.location='samp://your-server-ip:7777'">
        Играть
    </button>
</div>
```

</div>

<!-- TOP PLAYERS -->

<div class="section">
    <h2>🏆 Топ игроков</h2>

```
<div class="card">
    <?php while($row = $top->fetch_assoc()): ?>
        <div class="rank">
            <div><?= htmlspecialchars($row['name']) ?></div>
            <div>Lvl <?= $row['level'] ?></div>
            <div>$<?= number_format($row['money'], 0, '.', ' ') ?></div>
        </div>
    <?php endwhile; ?>
</div>
```

</div>

<!-- NEWS -->

<div class="section">
    <h2>📰 Новости проекта</h2>

```
<div class="grid">
    <?php if ($news): ?>
        <?php while($n = $news->fetch_assoc()): ?>
            <div class="card">
                <div class="news-title"><?= htmlspecialchars($n['title']) ?></div>
                <p><?= htmlspecialchars($n['content']) ?></p>
                <small><?= $n['created_at'] ?></small>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="card">Новостей пока нет</div>
    <?php endif; ?>
</div>
```

</div>

<!-- FOOTER -->

<footer>
    © <?= date("Y") ?> Empire RP. Все права защищены.
</footer>

</body>
</html>
