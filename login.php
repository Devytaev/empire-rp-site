<?php

require_once 'includes/database.php';
require_once 'includes/ratelimit.php';
require_once 'includes/security.php';

session_start();

rateLimit($_SERVER['REMOTE_ADDR']);
securityCheck();

$db = Database::connect();

$ip = $_SERVER['REMOTE_ADDR'];
$error = '';

// 📦 защита от IP-блоков
$checkBlock = $db->prepare("SELECT * FROM blocked_ips WHERE ip = ? LIMIT 1");
$checkBlock->bind_param("s", $ip);
$checkBlock->execute();
$blockResult = $checkBlock->get_result()->fetch_assoc();

if ($blockResult && $blockResult['blocked_until'] > time()) {
    die("⛔ IP временно заблокирован");
}

if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nickname = trim($_POST['nickname'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($nickname) || empty($password)) {
        $error = "Заполните все поля";
    } else {

        // 🔎 поиск аккаунта
        $stmt = $db->prepare("SELECT * FROM accounts WHERE name = ? LIMIT 1");
        $stmt->bind_param("s", $nickname);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();

        if (!$user) {

            $_SESSION['login_attempts']++;

            $log = $db->prepare("
                INSERT INTO site_login_logs (nickname, ip, status, created_at)
                VALUES (?, ?, 'fail', UNIX_TIMESTAMP())
            ");
            $log->bind_param("ss", $nickname, $ip);
            $log->execute();

            $error = "Аккаунт не найден";

        } else {

            $loginOk = false;

            // ✔ hash password
            if (password_verify($password, $user['password'])) {
                $loginOk = true;
            }

            // ✔ SA:MP plain password
            if ($password === $user['password']) {
                $loginOk = true;
            }

            if ($loginOk) {

                $_SESSION['login_attempts'] = 0;

                // ✔ лог успеха
                $status = 'success';
                $log = $db->prepare("
                    INSERT INTO site_login_logs (nickname, ip, status, created_at)
                    VALUES (?, ?, ?, UNIX_TIMESTAMP())
                ");
                $log->bind_param("sss", $nickname, $ip, $status);
                $log->execute();

                // ✔ обновление входа
                $time = time();

                $update = $db->prepare("
                    UPDATE accounts 
                    SET last_ip = ?, last_login = ?
                    WHERE id = ?
                ");
                $update->bind_param("sii", $ip, $time, $user['id']);
                $update->execute();

                // ✔ сессия
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['nickname'] = $user['name'];
                $_SESSION['admin'] = $user['admin'];

                header("Location: cabinet.php");
                exit;

            } else {

                $_SESSION['login_attempts']++;

                $log = $db->prepare("
                    INSERT INTO site_login_logs (nickname, ip, status, created_at)
                    VALUES (?, ?, 'fail', UNIX_TIMESTAMP())
                ");
                $log->bind_param("ss", $nickname, $ip);
                $log->execute();

                $error = "Неверный пароль";
            }
        }
    }

    // ⛔ блокировка после 5 попыток
    if ($_SESSION['login_attempts'] >= 5) {

        $until = time() + 600;

        $block = $db->prepare("
            INSERT INTO blocked_ips (ip, blocked_until)
            VALUES (?, ?)
            ON DUPLICATE KEY UPDATE blocked_until = VALUES(blocked_until)
        ");

        $block->bind_param("si", $ip, $until);
        $block->execute();

        $_SESSION['login_attempts'] = 0;
    }
}

?>

<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<title>Empire RP | Вход</title>

<style>
body {
    margin: 0;
    font-family: Arial;
    background: #0f1117;
    color: white;
}

.box {
    width: 400px;
    margin: 120px auto;
    background: #171a23;
    padding: 30px;
    border-radius: 15px;
}

input {
    width: 100%;
    padding: 12px;
    margin-top: 10px;
    border-radius: 10px;
    border: none;
    background: #0f1117;
    color: white;
}

button {
    width: 100%;
    margin-top: 15px;
    padding: 12px;
    background: #ffb300;
    border: none;
    border-radius: 10px;
    font-weight: bold;
    cursor: pointer;
}

.error {
    color: red;
    text-align: center;
    margin-top: 10px;
}
</style>
</head>

<body>

<div class="box">
<h2>Empire RP Login</h2>

<form method="post">

<input type="text" name="nickname" placeholder="Ник" required>
<input type="password" name="password" placeholder="Пароль" required>

<button type="submit">Войти</button>

</form>

<?php if ($error): ?>
<div class="error"><?= $error ?></div>
<?php endif; ?>

</div>

</body>
</html>