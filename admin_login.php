<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/functions.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? AND role = 'admin'");
    $stmt->execute([$username]);
    $admin = $stmt->fetch();

    if ($admin && hash('sha256', $password) === $admin['password']) {
        $_SESSION['user_id'] = $admin['id'];
        $_SESSION['username'] = $admin['username'];
        $_SESSION['role'] = $admin['role'];
        redirect('admin_panel.php');
    } else {
        $errors[] = "Неверный логин или пароль администратора.";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Вход администратора - Грузовозофф</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h2>Вход администратора</h2>

    <?php
    if (!empty($errors)) {
        echo '<div class="error"><ul>';
        foreach ($errors as $e) echo "<li>$e</li>";
        echo '</ul></div>';
    }
    ?>

    <form method="post">
        <label>Логин: <input type="text" name="username" required></label><br>
        <label>Пароль: <input type="password" name="password" required></label><br>
        <button type="submit">Войти как админ</button>
    </form>

    <p><a href="login.php">Назад ко входу пользователя</a></p>
</body>
</html>
