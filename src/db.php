<?php
function connectDB()
// connectDB() という関数を定義しています。呼び出すと、MySQLデータベースへの接続を行い、成功すればPDOインスタンスを返す、失敗すればエラーメッセージを表示して終了します。
{
    // データベースの接続情報
    $host = 'db';
    $dbname = 'myapp';
    $user = 'myuser';
    $password = 'mypassword';

    // データベースに接続
    try {
        return new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $password);
        // new PDO(...) で **データベース接続オブジェクト（PDO）**を生成して接続
    } catch (PDOException $e) { //接続失敗時の処理
        // エラーが発生したら、$e に例外オブジェクトが入ってくる
        echo "DB接続エラー: " . $e->getMessage(); // エラーメッセージを表示
        exit;
    }
}
