<?php
require_once __DIR__ . '/../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $message = trim($_POST['message']);

    $pdo = connectDB();
    $stmt = $pdo->prepare("INSERT INTO posts (name, message) VALUES (:name, :message)");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':message', $message);
    $stmt->execute();
}

header('Location: list.php');
exit;
