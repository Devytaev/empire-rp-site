<?php
require_once '../includes/admin_auth.php';
require_once '../includes/database.php';

$db = Database::connect();

if (isset($_GET['ban'])) {
    $id = (int)$_GET['ban'];
    $db->query("UPDATE accounts SET warn = warn + 1 WHERE id = $id");
    header("Location: users.php");
    exit;
}

$users = $db->query("SELECT id, name, level, money, admin FROM accounts ORDER BY id DESC LIMIT 100");
?>

<!DOCTYPE html>

<html lang="ru">
<head>
<meta charset="UTF-8">
<title>Игроки</title>

<style>
body {background:#0f1117;color:white;font-family:Arial;}
table {width:100%;border-collapse:collapse;}
th,td {padding:10px;border-bottom:1px solid #333;}
a {color:#ffb300;text-decoration:none;}
</style>

</head>
<body>

<h2>👤 Игроки</h2>

<table>
<tr>
<th>ID</th>
<th>Ник</th>
<th>Lvl</th>
<th>Money</th>
<th>Admin</th>
<th>Действие</th>
</tr>

<?php while($u = $users->fetch_assoc()): ?>

<tr>
<td><?= $u['id'] ?></td>
<td><?= $u['name'] ?></td>
<td><?= $u['level'] ?></td>
<td><?= $u['money'] ?></td>
<td><?= $u['admin'] ?></td>
<td>
<a href="?ban=<?= $u['id'] ?>">+warn</a>
</td>
</tr>
<?php endwhile; ?>

</table>

</body>
</html>
