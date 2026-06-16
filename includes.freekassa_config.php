<?php

// FreeKassa ID магазина
define('FK_SHOP_ID', 'YOUR_SHOP_ID');

// 1-й и 2-й секретные ключи из FreeKassa
define('FK_SECRET_1', 'YOUR_SECRET_1');
define('FK_SECRET_2', 'YOUR_SECRET_2');

// URL callback (обязательно https желательно)
define('FK_RESULT_URL', 'https://your-site.com/freekassa_result.php');
define('FK_SUCCESS_URL', 'https://your-site.com/donate.php?success=1');
define('FK_FAIL_URL', 'https://your-site.com/donate.php?fail=1');

?>
