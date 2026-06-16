<?php

$uri = $_SERVER['REQUEST_URI'];

$blocked = [
    'phpmyadmin',
    '.env',
    'config.php',
    'wp-admin',
    'eval(',
];

foreach ($blocked as $b) {
    if (str_contains($uri, $b)) {
        http_response_code(403);
        die("Forbidden");
    }
}