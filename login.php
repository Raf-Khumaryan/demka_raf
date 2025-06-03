<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/functions.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? AND role = 'user'");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && hash('sha256', $password) === $user['password']) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        redirect('dashboard.php');
    } else {
        $errors[] = "Неверный логин или пароль.";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Вход - Грузовозофф</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h2>Вход пользователя</h2>

    <?php
    if (!empty($errors)) {
        echo '<div class="error"><ul>';
        foreach ($errors as $e) echo "<li>$e</li>";
        echo '</ul></div>';
    }

    if (isset($_SESSION['success'])) {
        echo '<div class="success">' . $_SESSION['success'] . '</div>';
        unset($_SESSION['success']);
    }
    ?>

    <form method="post">
        <label>Логин: <input type="text" name="username" required></label><br>
        <label>Пароль: <input type="password" name="password" required></label><br>
        <button type="submit">Войти</button>
    </form>

    <p><a href="register.php">Зарегистрироваться</a></p>
    <p><a href="admin_login.php">Войти как администратор</a></p>
</body>
</html>