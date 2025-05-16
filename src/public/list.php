<?php
require_once __DIR__ . '/../db.php';
$pdo = connectDB();

$stmt = $pdo->query("SELECT * FROM posts ORDER BY created_at DESC");
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>投稿一覧</title>
</head>

<body>
    <h1>投稿一覧</h1>
    <p><a href="index.php">← 戻る</a></p>

    <?php if ($posts): ?>
        <ul>
            <?php foreach ($posts as $post): ?>
                <li>
                    <strong><?= htmlspecialchars($post['name']) ?></strong>
                    （<?= $post['created_at'] ?>）<br>
                    <?= nl2br(htmlspecialchars($post['message'])) ?>
                </li>
                <hr>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>まだ投稿がありません。</p>
    <?php endif; ?>
</body>

</html>