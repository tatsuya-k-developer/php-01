<?php session_start(); ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>お問い合わせフォーム</title>
</head>

<body>
    <h1>お問い合わせフォーム</h1>
    <form action="submit.php" method="POST">
        <p>名前：<input type="text" name="name" required></p>
        <p>メッセージ：<textarea name="message" required></textarea></p>
        <p><button type="submit">送信</button></p>
    </form>
    <p><a href="list.php">投稿一覧を見る</a></p>
</body>

</html>