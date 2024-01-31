<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if($_SERVER["REQUEST_METHOD"] == 'POST'){
    $name = test_input($_POST["name"]);
    $email = test_input($_POST["email"]);
    $lastname = test_input($_POST["lastname"]);
    $age = test_input($_POST["age"]);
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
} 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Development With Php</title>
</head>
<body>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
    <label for="name">First name:</label><br>
    <input type="text" id="name" name="name" value="Ali"><br>
    <label for="lastname">Last name:</label><br>
    <input type="text" id="lastname" name="lastname" value="Yilmaz"><br><br>
    <label for="email">Email:</label><br>
    <input type="text" id="email" name="email" value="okethis@gmail.com"><br>
    <label for="age">Age:</label><br>
    <input type="text" id="age" name="age" value="12"><br>
    <input type="submit" value="Submit">
    </form>
</body>
</html>