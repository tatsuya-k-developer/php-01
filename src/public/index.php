<?php session_start(); ?>
<!-- 
セッションを開始するPHPの命令です。

将来、ユーザーのログイン情報やエラーメッセージなどを一時的に保存しておくときに使います。

今の段階では特に使っていませんが、掲示板ではよく使うため、最初に書いておくことが多いです。 

セッションについて
    セッションとはユーザーごとの一時的なデータをサーバー側に保存しておく仕組みです。
    ユーザーがWebサイトにアクセスした瞬間にセッションが開始されます。
    サーバーはそのユーザーごとにデータ（例：名前、ログイン状態など）を一時的に記憶できます。
    記憶された情報は、ページを移動しても使い続けることができます。
    ユーザーがブラウザを閉じたり、一定時間が過ぎるとセッションは自動的に消えます（終了）。

-->



<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>PHP簡易掲示板</title>
</head>

<body>
    <h1>入力欄</h1>

    <form action="submit.php" method="POST">
        <!-- 
    action="submit.php" は、フォームで入力された内容を送る先のファイルを指定しています。 
    method="POST" は送信方法の指定。
    POST はフォームの中身（名前・メッセージ）を目に見えない形で安全に送る方法です。 
    -->
        <p>名前：<input type="text" name="name" required></p>
        <p>メッセージ：<textarea name="message" required></textarea></p>
        <p><button type="submit">送信</button></p>
    </form>

    <p><a href="list.php">投稿一覧を見る</a></p>
</body>

</html>