<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

include_once 'Database.php';

DB::Init();

if ($_SERVER["REQUEST_METHOD"] == 'POST') {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $sql = sprintf("SELECT * FROM user WHERE email ='%s'", DB::get()->real_escape_string($email));
    $result = DB::get()->query($sql);

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            session_regenerate_id();
            $_SESSION["user_id"] = $user["id"];
            $_SESSION['user_email'] = $user['email'];
            header('Location: index.php');
            exit();
        } else {
            header('Location: login.view.php');
            exit();
        }
    } else {
        header('Location: error.php');
        exit();
    }
}
