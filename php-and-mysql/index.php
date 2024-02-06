<?php
include_once "./Database.php";
$database = Database::Initialize("localhost", "todo", "root", "2901");


header("Location: login.view.php");