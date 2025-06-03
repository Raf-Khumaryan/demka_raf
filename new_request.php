<?php
require_once 'includes/auth_check.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $transport_datetime = sanitize($_POST['transport_datetime']);
    $weight = floatval($_POST['weight']);
    $dimensions = sanitize($_POST['dimensions']);
    $departure_address = sanitize($_POST['departure_address']);
    $delivery_address = sanitize($_POST['delivery_address']);
    $cargo_type = sanitize($_POST['cargo_type']);

    // Валидация
    if (!$transport_datetime || !$weight || !$dimensions || !$departure_address || !$delivery_address || !$cargo_type) {
        $errors[] = "Все поля обязательны.";
    }

    if ($weight <= 0) {
        $errors[] = "Вес должен быть положительным.";
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO requests 
            (user_id, cargo_type, weight, dimensions, departure_address, delivery_address, transport_datetime) 
            VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $_SESSION['user_id'], $cargo_type, $weight, $dimensions,
            $departure_address, $delivery_address, $transport_datetime
        ]);

        $_SESSION['success'] = "Заявка успешно отправлена!";
        redirect('dashboard.php');
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Новая заявка - Грузовозофф</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h2>Создание заявки</h2>

    <?php
    if (!empty($errors)) {
        echo '<div class="error"><ul>';
        foreach ($errors as $e) echo "<li>$e</li>";
        echo '</ul></div>';
    }
    ?>

    <form method="post">
        <label>Дата и время перевозки: <input type="datetime-local" name="transport_datetime" required></label><br>
        <label>Вес (кг): <input type="number" name="weight" min="1" step="0.1" required></label><br>
        <label>Габариты (например: 2x1x1 м): <input type="text" name="dimensions" required></label><br>
        <label>Адрес отправления: <input type="text" name="departure_address" required></label><br>
        <label>Адрес доставки: <input type="text" name="delivery_address" required></label><br>
        <label>Тип груза:
            <select name="cargo_type" required>
                <option value="">-- Выберите тип --</option>
                <option value="Хрупкое">Хрупкое</option>
                <option value="Скоропортящееся">Скоропортящееся</option>
                <option value="Требуется рефрижератор">Требуется рефрижератор</option>
                <option value="Животные">Животные</option>
                <option value="Жидкость">Жидкость</option>
                <option value="Мебель">Мебель</option>
                <option value="Мусор">Мусор</option>
            </select>
        </label><br>

        <button type="submit">Отправить заявку</button>
    </form>

    <p><a href="dashboard.php">Назад</a></p>
</body>
</html>