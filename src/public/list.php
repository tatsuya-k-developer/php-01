<?php
require_once __DIR__ . '/../db.php';  // DB接続用ファイルを読み込む
$pdo = connectDB();                  // connectDB() で PDOインスタンスを取得

$stmt = $pdo->query("SELECT * FROM posts ORDER BY created_at DESC");
// postsテーブルから投稿を作成日時の降順で取得

$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
// 結果を連想配列の配列として取得（1投稿 = 1行）
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
        <!-- $posts が空でない（＝投稿が1件以上ある）場合に表示。 -->
        <ul>
            <?php foreach ($posts as $post): ?>
                <!-- 各投稿データに対してループ処理を実行。 -->
                <li>
                    <strong><?= htmlspecialchars($post['name']) ?></strong> <!-- 投稿者名を表示 -->
                    （<?= $post['created_at'] ?>）<br> <!-- 投稿日時を表示 -->
                    <?= nl2br(htmlspecialchars($post['message'])) ?> <!-- 投稿内容を表示 -->
                </li>
                <hr>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>まだ投稿がありません。</p>
    <?php endif; ?>
</body>

</html>