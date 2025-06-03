<?php
require_once 'includes/auth_check.php';

$user_id = $_SESSION['user_id'];

// Получить заявки пользователя
$stmt = $pdo->prepare("SELECT * FROM requests WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$user_id]);
$requests = $stmt->fetchAll();

// Получить отзыв, если он есть
$revStmt = $pdo->prepare("SELECT * FROM reviews WHERE user_id = ?");
$revStmt->execute([$user_id]);
$review = $revStmt->fetch();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Кабинет - Грузовозофф</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .modal {
            display: none; position: fixed; top: 0; left: 0;
            width: 100%; height: 100%; background: rgba(0,0,0,0.5);
            justify-content: center; align-items: center;
        }
        .modal-content {
            background: white; padding: 20px; border-radius: 10px;
            width: 400px; max-width: 90%;
        }
    </style>
</head>
<body>
    <h2>Добро пожаловать, <?= htmlspecialchars($_SESSION['username']) ?></h2>

    <p><a href="new_request.php">Создать заявку</a> | 
       <a href="edit_profile.php">Редактировать данные</a> | 
       <a href="logout.php">Выход</a></p>

    <h3>Ваши заявки</h3>

    <?php if ($requests): ?>
        <ul>
            <?php foreach ($requests as $req): ?>
                <li>
                    <strong><?= htmlspecialchars($req['cargo_type']) ?></strong> —
                    <?= htmlspecialchars($req['weight']) ?> кг, 
                    <?= htmlspecialchars($req['dimensions']) ?> <br>
                    С: <?= htmlspecialchars($req['departure_address']) ?> → До: <?= htmlspecialchars($req['delivery_address']) ?><br>
                    Дата: <?= htmlspecialchars($req['transport_datetime']) ?><br>
                    Статус: <strong><?= htmlspecialchars($req['status']) ?></strong>
                    <hr>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Вы ещё не создавали заявок.</p>
    <?php endif; ?>

    <h3>Отзыв</h3>
    <?php if ($review): ?>
        <p><strong>Вы уже оставили отзыв:</strong></p>
        <blockquote><?= htmlspecialchars($review['comment']) ?></blockquote>
    <?php else: ?>
        <button onclick="document.getElementById('reviewModal').style.display='flex'">Оставить отзыв</button>
    <?php endif; ?>

    <!-- Модальное окно отзыва -->
    <div class="modal" id="reviewModal">
        <div class="modal-content">
            <h4>Оставить отзыв</h4>
            <form method="post" action="submit_review.php">
                <textarea name="comment" rows="4" required></textarea><br>
                <button type="submit">Отправить</button>
                <button type="button" onclick="document.getElementById('reviewModal').style.display='none'">Отмена</button>
            </form>
        </div>
    </div>

    <script>
        window.onclick = function(e) {
            const modal = document.getElementById('reviewModal');
            if (e.target === modal) modal.style.display = "none";
        }
    </script>
</body>
</html>