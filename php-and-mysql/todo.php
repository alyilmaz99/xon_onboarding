<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
include_once "./Database.php";
DB::Init();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["todo"])) {
    $todo = $_POST["todo"];
    $user_id = $_SESSION["user_id"];

    $sql = "INSERT INTO todo (user_id, content) VALUES (?, ?)";
    $stmt = DB::get()->stmt_init();

    if (!$stmt->prepare($sql)) {
        die("SQL error: " . DB::get()->error);
    }

    $stmt->bind_param("ss", $user_id, $todo);
    if (!$stmt->execute()) {
        die("SQL error: " . $stmt->error . " Error number: " . DB::get()->errno);
    }
    exit();
}