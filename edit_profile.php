<?php
require_once 'includes/auth_check.php';

$user_id = $_SESSION['user_id'];
$errors = [];

// Получение текущих данных
$stmt = $pdo->prepare("SELECT full_name, phone, email FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    redirect('dashboard.php');
}

// Обработка формы
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = sanitize($_POST['full_name']);
    $phone = sanitize($_POST['phone']);
    $email = sanitize($_POST['email']);

    // Валидация
    if (!preg_match('/^[А-Яа-яЁё\s]+$/u', $full_name)) {
        $errors[] = "ФИО должно содержать только кириллицу и пробелы.";
    }
    if (!validatePhone($phone)) {
        $errors[] = "Телефон должен быть в формате +7(XXX)-XXX-XX-XX.";
    }
    if (!validateEmail($email)) {
        $errors[] = "Некорректный email.";
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("UPDATE users SET full_name = ?, phone = ?, email = ? WHERE id = ?");
        $stmt->execute([$full_name, $phone, $email, $user_id]);
        $_SESSION['success'] = "Данные успешно обновлены.";
        redirect('dashboard.php');
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Редактирование профиля</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h2>Редактирование профиля</h2>

    <?php
    if (!empty($errors)) {
        echo '<div class="error"><ul>';
        foreach ($errors as $e) echo "<li>$e</li>";
        echo '</ul></div>';
    }
    ?>

    <form method="post">
        <label>ФИО: <input type="text" name="full_name" value="<?= htmlspecialchars($user['full_name']) ?>" required></label><br>
        <label>Телефон: <input type="text" name="phone" value="<?= htmlspecialchars($user['phone']) ?>" required></label><br>
        <label>Email: <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required></label><br>
        <button type="submit">Сохранить изменения</button>
    </form>

    <p><a href="dashboard.php">Назад</a></p>
</body>
</html>