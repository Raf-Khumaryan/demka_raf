<?php

// Проверка авторизации
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Проверка администратора
function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

// Перенаправление
function redirect($url) {
    header("Location: $url");
    exit;
}

// Очистка входных данных
function sanitize($data) {
    return htmlspecialchars(trim($data));
}

// Валидация телефона
function validatePhone($phone) {
    return preg_match('/^\+7\(\d{3}\)-\d{3}-\d{2}-\d{2}$/', $phone);
}

// Валидация email
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}
?>