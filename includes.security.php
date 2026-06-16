<?php

// --- XSS защита ---
function e($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

// --- простая фильтрация входных данных ---
function clean($data) {
    return trim(strip_tags($data));
}

// --- проверка IP (очень простая защита от флуда) ---
function getIP() {
    return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
}

// --- блок подозрительных запросов ---
function securityCheck() {
    $badPatterns = [
        'select', 'union', 'insert', 'drop', 'update', 'delete',
        '<script', '../', 'base64', 'sleep('
    ];

    $input = strtolower(json_encode($_REQUEST));

    foreach ($badPatterns as $pattern) {
        if (str_contains($input, $pattern)) {
            http_response_code(403);
            die("Blocked by security system");
        }
    }
}

securityCheck();