<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/functions.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = sanitize($_POST['full_name']);
    $phone = sanitize($_POST['phone']);
    $email = sanitize($_POST['email']);
    $username = sanitize($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Валидация
    if (mb_strlen($username) < 6 || !preg_match('/^[А-Яа-яЁё]+$/u', mb_substr($username, 0, 1))) {
        $errors[] = "Логин должен быть не менее 6 символов и начинаться с кириллической буквы.";
    }

    if (mb_strlen($password) < 6) {
        $errors[] = "Пароль должен содержать минимум 6 символов.";
    }

    if ($password !== $confirm_password) {
        $errors[] = "Пароли не совпадают.";
    }

    if (!preg_match('/^[А-Яа-яЁё\s]+$/u', $full_name)) {
        $errors[] = "ФИО должно содержать только кириллицу и пробелы.";
    }

    if (!validatePhone($phone)) {
        $errors[] = "Телефон должен быть в формате +7(XXX)-XXX-XX-XX.";
    }

    if (!validateEmail($email)) {
        $errors[] = "Некорректный email.";
    }

    // Проверка логина
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$username]);
    if ($stmt->fetch()) {
        $errors[] = "Такой логин уже существует.";
    }

    // Если ошибок нет — сохранить
    if (empty($errors)) {
        $hash = hash('sha256', $password);
        $stmt = $pdo->prepare("INSERT INTO users (full_name, phone, email, username, password) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$full_name, $phone, $email, $username, $hash]);

        $_SESSION['success'] = "Вы успешно зарегистрированы!";
        redirect('login.php');
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Регистрация - Грузовозофф</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h2>Регистрация</h2>

    <?php
    if (!empty($errors)) {
        echo '<div class="error"><ul>';
        foreach ($errors as $e) {
            echo "<li>$e</li>";
        }
        echo '</ul></div>';
    }
    ?>

    <form method="post" action="">
        <label>ФИО: <input type="text" name="full_name" required></label><br>
        <label>Телефон: <input type="text" name="phone" placeholder="+7(900)-123-45-67" required></label><br>
        <label>Email: <input type="email" name="email" required></label><br>
        <label>Логин: <input type="text" name="username" required></label><br>
        <label>Пароль: <input type="password" name="password" required></label><br>
        <label>Повторите пароль: <input type="password" name="confirm_password" required></label><br>
        <button type="submit">Зарегистрироваться</button>
    </form>

    <p><a href="login.php">Уже зарегистрированы?</a></p>
</body>
</html>