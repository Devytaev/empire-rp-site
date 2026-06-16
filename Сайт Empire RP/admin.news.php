<?php
require_once '../includes/admin_auth.php';
require_once '../includes/database.php';

$db = Database::connect();

/* ДОБАВЛЕНИЕ НОВОСТИ */
if (isset($_POST['add'])) {

    $title = $_POST['title'];
    $content = $_POST['content'];
    $author = $_SESSION['nickname'] ?? 'Admin';

    $stmt = $db->prepare("
        INSERT INTO site_news (title, content, author, created_at)
        VALUES (?, ?, ?, UNIX_TIMESTAMP())
    ");

    $stmt->bind_param("sss", $title, $content, $author);
    $stmt->execute();
}

/* УДАЛЕНИЕ */
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $db->query("DELETE FROM site_news WHERE id = $id");
    header("Location: news.php");
    exit;
}

$news = $db->query("SELECT * FROM site_news ORDER BY id DESC");
?>

<!DOCTYPE html>

<html lang="ru">
<head>
<meta charset="UTF-8">
<title>Админка | Новости</title>

<style>
body {background:#0f1117;color:white;font-family:Arial;}
.container {padding:20px;}
input,textarea {
    width:100%;
    padding:10px;
    margin:5px 0;
    background:#171a23;
    border:none;
    color:white;
}
button {
    padding:10px 20px;
    background:#ffb300;
    border:none;
    cursor:pointer;
    font-weight:bold;
}
.card {background:#171a23;padding:15px;margin:10px 0;border-radius:10px;}
a {color:#ffb300;text-decoration:none;}
</style>

</head>

<body>

<div class="container">

<h2>📰 Управление новостями</h2>

<!-- ФОРМА -->

<form method="post">
    <input type="text" name="title" placeholder="Заголовок" required>
    <textarea name="content" placeholder="Текст новости" required></textarea>
    <button name="add">Добавить новость</button>
</form>

<hr>

<!-- СПИСОК -->

<?php while($n = $news->fetch_assoc()): ?>

<div class="card">
    <h3><?= htmlspecialchars($n['title']) ?></h3>
    <p><?= nl2br(htmlspecialchars($n['content'])) ?></p>
    <small>
        Автор: <?= $n['author'] ?> | 
        <?= date("d.m.Y H:i", $n['created_at']) ?>
    </small>
    <br><br>
    <a href="?delete=<?= $n['id'] ?>">Удалить</a>
</div>
<?php endwhile; ?>

</div>

</body>
</html>
