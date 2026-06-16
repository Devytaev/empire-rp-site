<?php
require_once 'auth.php';

function isAdmin() {
    return Auth::check() && (int)Auth::user()['admin'] > 0;
}

if (!isAdmin()) {
    http_response_code(403);
    die("Access denied");
}