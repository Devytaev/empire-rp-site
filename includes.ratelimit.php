<?php

function rateLimit($key, $maxAttempts = 5, $timeWindow = 60) {

    if (!isset($_SESSION['rate'][$key])) {
        $_SESSION['rate'][$key] = [];
    }

    // очищаем старые попытки
    $_SESSION['rate'][$key] = array_filter(
        $_SESSION['rate'][$key],
        function ($t) use ($timeWindow) {
            return ($t + $timeWindow) > time();
        }
    );

    if (count($_SESSION['rate'][$key]) >= $maxAttempts) {
        die("Too many attempts. Try later.");
    }

    $_SESSION['rate'][$key][] = time();
}