<?php
$host = 'localhost';
$db = 'gruzovozoff';
$user = 'root'; // замените на своего пользователя БД
$pass = '';     // и пароль, если установлен

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Ошибка подключения к БД: " . $e->getMessage());
}
?>