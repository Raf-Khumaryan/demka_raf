<?php
session_start();

if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] === 'admin') {
        header('Location: admin_panel.php');
        exit;
    } else {
        header('Location: dashboard.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Грузовозофф - Главная</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>Портал "Грузовозофф"</h1>
    <p>Информационная система для оформления заявок на грузоперевозку</p>

    <p>
        <a href="login.php">Вход пользователя</a> |
        <a href="admin_login.php">Вход администратора</a> |
        <a href="register.php">Регистрация</a>
    </p>
</body>
</html>
