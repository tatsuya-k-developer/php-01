<?php

// フォームで入力された名前・メッセージをデータベースに保存する処理を書いてるPHPのファイルです。


require_once __DIR__ . '/../db.php';
// db.phpを読み込む,これにより、connectDB() 関数が使えるようになります。

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // フォームがPOST送信された場合だけ処理するという条件です。安全対策でもあり、「直接アクセスされたときは何もしない」という役割もあります。
    $name = trim($_POST['name']);
    // フォームで入力された名前を取得し、前後の空白を削除します。
    $message = trim($_POST['message']);
    // フォームで入力されたメッセージを取得し、前後の空白を削除します。


    $pdo = connectDB();
    //db.php にある connectDB() 関数で、データベースに接続しています。
    $stmt = $pdo->prepare("INSERT INTO posts (name, message) VALUES (:name, :message)");
    // prepare() で SQL文を事前に準備します。:name と :message は プレースホルダと呼ばれ、後で値を埋め込みます。
    $stmt->bindParam(':name', $name);
    // bindParam() で プレースホルダに値($name)を埋め込みます。
    $stmt->bindParam(':message', $message);
    // bindParam() で プレースホルダに値($message)を埋め込みます。
    $stmt->execute();
    // execute() で SQL文を実行します。実際にデータベースにレコード（投稿）が追加されます。
} else {
    http_response_code(405); // 405 Method Not Allowed
    echo "不正なアクセスです";
    exit;
}

header('Location: list.php');
exit;

// 投稿が完了したら、投稿一覧ページ（list.php）に自動的に移動させます。
// header() はリダイレクト処理で、exit を使うことで余計なコードの実行を防ぎます。
