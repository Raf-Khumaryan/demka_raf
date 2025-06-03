<?php
require_once 'includes/auth_check.php';

if (!isAdmin()) {
    redirect('login.php');
}

// Обработка смены статуса
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['request_id'], $_POST['status'])) {
    $request_id = intval($_POST['request_id']);
    $status = $_POST['status'];
    $allowed_statuses = ['Новая', 'В работе', 'Отменена', 'Доставлено', 'Завершено'];

    if (in_array($status, $allowed_statuses)) {
        $stmt = $pdo->prepare("UPDATE requests SET status = ? WHERE id = ?");
        $stmt->execute([$status, $request_id]);
    }
}

// Получить все заявки с данными пользователей
$stmt = $pdo->query("
    SELECT r.*, u.full_name, u.phone, u.email 
    FROM requests r
    JOIN users u ON r.user_id = u.id
    ORDER BY r.created_at DESC
");
$requests = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Админ-панель - Грузовозофф</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h2>Административная панель</h2>

    <p><a href="logout.php">Выйти</a></p>

    <?php if ($requests): ?>
        <table border="1" cellpadding="5" cellspacing="0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Пользователь</th>
                    <th>Контакты</th>
                    <th>Тип груза</th>
                    <th>Вес</th>
                    <th>Габариты</th>
                    <th>Адреса</th>
                    <th>Дата перевозки</th>
                    <th>Статус</th>
                    <th>Изменить статус</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($requests as $r): ?>
                <tr>
                    <td><?= $r['id'] ?></td>
                    <td><?= htmlspecialchars($r['full_name']) ?></td>
                    <td>
                        <?= htmlspecialchars($r['phone']) ?><br>
                        <?= htmlspecialchars($r['email']) ?>
                    </td>
                    <td><?= htmlspecialchars($r['cargo_type']) ?></td>
                    <td><?= htmlspecialchars($r['weight']) ?> кг</td>
                    <td><?= htmlspecialchars($r['dimensions']) ?></td>
                    <td>
                        Откуда: <?= htmlspecialchars($r['departure_address']) ?><br>
                        Куда: <?= htmlspecialchars($r['delivery_address']) ?>
                    </td>
                    <td><?= htmlspecialchars($r['transport_datetime']) ?></td>
                    <td><strong><?= htmlspecialchars($r['status']) ?></strong></td>
                    <td>
                        <form method="post">
                            <input type="hidden" name="request_id" value="<?= $r['id'] ?>">
                            <select name="status" onchange="this.form.submit()">
                                <?php
                                $statuses = ['Новая', 'В работе', 'Отменена', 'Доставлено', 'Завершено'];
                                foreach ($statuses as $status) {
                                    $selected = $r['status'] === $status ? 'selected' : '';
                                    echo "<option value=\"$status\" $selected>$status</option>";
                                }
                                ?>
                            </select>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Заявок пока нет.</p>
    <?php endif; ?>
</body>
</html>