<?php
require_once 'includes/auth_check.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $comment = sanitize($_POST['comment']);

    if (!empty($comment)) {
        $stmt = $pdo->prepare("INSERT INTO reviews (user_id, comment) VALUES (?, ?)");
        $stmt->execute([$_SESSION['user_id'], $comment]);
    }
}

redirect('dashboard.php');