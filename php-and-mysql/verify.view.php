<?php
$verification_code = $_GET['verification_code'];
$email = $_GET['email'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include_once 'Database.php';
    $database = Database::Initialize("localhost", "todo", "root", "");

    $db = Database::getInstance()->getConnection();

    $sql = "UPDATE user SET is_verified = 1 WHERE email = '$email' AND $verification_code = '$verification_code'";
    $stmt = $db->prepare($sql);
    if (!$stmt->execute()) {
        echo "hata";
    } else {

        header("Location: index.php");
        exit();
    }
}



?>

<!doctype html>
<html>

<head>
    <meta name="viewport" content="width=device-width" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Verify Email</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <div class="screen">
            <div class="screen__content">
                <h1>EMAIL VERIFIED</h1>
                <h2>You are just one step away</h2>
                <form method="post">
                    <div class="login__field">
                        <input type="submit" class="login__submit" value="Kayıt Ol">
                        <i class="login__icon fas fa-chevron-right"></i>
                    </div>
                </form>
                <p>XON</p>
            </div>
            <div class="screen__background">
                <div class="screen__background__shape screen__background__shape1"></div>
                <div class="screen__background__shape screen__background__shape2"></div>
                <div class="screen__background__shape screen__background__shape3"></div>
                <div class="screen__background__shape screen__background__shape4"></div>
            </div>
        </div>
    </div>
</body>

</html>