<?php
ini_set('display_errors', 1);
ini_set('session.gc_maxlifetime', 10 * 60 * 60 * 60);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

if (isset($_SESSION['name'])) {
    header('Location: info.view.php');
    exit();
}

include_once 'Validator.php';

$validator = Validator::getInstance();

if ($_SERVER["REQUEST_METHOD"] == 'POST') {
    $name = $validator->textValidation($_POST['name']);
    $email = $validator->emailValidation($_POST["email"]);
    $lastname =  $validator->textValidation($_POST['lastname']);
    $age = $validator->ageValidation($_POST["age"]);
    $website = $validator->websiteValidation($_POST["website"]);

    if ($validator->isValid) {
        $_SESSION['name']  = $name['data'];
        $_SESSION['email'] = $email['data'];
        $_SESSION['lastname'] = $lastname['data'];
        $_SESSION['age'] = $age['data'];
        $_SESSION['website'] = $website['data'];
        header('Location: info.view.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Development With Php</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="<?php echo isset($_COOKIE['theme']) && $_COOKIE['theme'] == 'dark' ?  "dark.css" : "style.css"; ?>">

</head>

<body>
    <div class="container ">
        <div class="row">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

                <label for="name">First name:</label><br>
                <input type="text" id="name" name="name" value="<?php echo isset($name['data']) ? $name['data'] : ''; ?>"><?php echo isset($name['isValid']) && $name['isValid'] === false ? $name['error'] : ""; ?><br>

                <label for="lastname">Last name:</label><br>
                <input type="text" id="lastname" name="lastname" value="<?php echo isset($lastname['data']) ? $lastname['data'] : ''; ?>"><?php echo isset($lastname['isValid']) && $lastname['isValid'] === false ? $lastname['error'] : ""; ?><br>

                <label for="email">Email:</label><br>
                <input type="text" id="email" name="email" value="<?php echo isset($email['data']) ? $email['data'] : ''; ?>"><?php echo isset($email['isValid']) && $email['isValid'] == false ? $email['error'] : ''; ?><br>

                <label for="age">Age:</label><br>
                <input type="text" id="age" name="age" value="<?php echo isset($age['data']) ? $age['data'] : ''; ?>"><?php echo isset($age['isValid']) && $age['isValid'] == false ? $age['error'] : '';  ?><br>

                <label for="website">Website:</label><br>
                <input type="text" id="website" name="website" value="<?php echo isset($website['data']) ? $website['data'] : ''; ?>"><?php echo isset($website['isValid']) && $website['isValid'] == false ? $website['error'] : ''; ?><br><br>
                <input class="submit" type="submit" value="Submit">
            </form>
        </div>

</body>

</html>