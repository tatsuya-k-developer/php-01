<?php
function connectDB()
{
    $host = 'db';
    $dbname = 'myapp';
    $user = 'myuser';
    $password = 'mypassword';

    try {
        return new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $password);
    } catch (PDOException $e) {
        echo "DB接続エラー: " . $e->getMessage();
        exit;
    }
}
