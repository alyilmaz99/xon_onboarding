<?php
session_start();
include_once 'Database.php';

if ($_SERVER["REQUEST_METHOD"] == 'POST') {
    $database = Database::Initialize("localhost", "todo", "root", "");

    $db = Database::getInstance()->getConnection();

    $sql = "SELECT * FROM user WHERE email = '{$_POST["email"]}'";

    $stmt = $db->prepare($sql);
    if (!$stmt->execute()) {
        header("Location: error.php");
        exit();
    } else {
        $user = $stmt->fetch();
        if (password_verify($_POST['password'], $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            header("Location: index.php");
            exit();
        } else {
            header("Location: login.view.php");
            exit();
        }
    }
} else {
    header("Location: error.php");
    exit();
}