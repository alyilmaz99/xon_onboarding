<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once 'Validator.php';
$validator = Validator::getInstance();
if ($_SERVER["REQUEST_METHOD"] == 'POST') {
    $name = $validator->textValidation($_POST['name']);
    $email = $validator->emailValidation($_POST["email"]);
    $phone = $validator->phoneValidation($_POST["phone"]);

    if ($validator->isValid) {
        include_once 'login.controller.php';
        $signUp =  new LoginController();

        $_SESSION['name']  = $name['data'];
        $_SESSION['email'] = $email['data'];
        $_SESSION['phone'] = $phone['data'];
        $signUp->signUp($name['data'], $email['data'], $_POST['password'], $phone['data']);
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">

    <title>INDEX</title>
</head>

<body>
    <div class="container">
        <div class="screen">
            <div class="screen__content">
                <form class="login" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="login__field">
                        <input type="text" id="name" name="name" class="login__input" placeholder="<?php echo isset($name['data']) ? $name['data'] : 'Name'; ?>"><?php echo isset($name['isValid']) && $name['isValid'] === false ? $name['error'] : ""; ?>
                    </div>
                    <div class="login__field">
                        <input type="password" id="password" name="password" class="login__input" placeholder="Password">
                    </div>
                    <div class="login__field">
                        <input type="email" id="email" name="email" class="login__input" placeholder="<?php echo isset($email['data']) ? $email['data'] : 'E-Mail'; ?>"><?php echo isset($mail['isValid']) && $mail['isValid'] === false ? $mail['error'] : ""; ?>
                    </div>
                    <div class="login__field">
                        <input type="phone" id="phone" name="phone" class="login__input" placeholder="<?php echo isset($phone['data']) ? $phone['data'] : 'Phone'; ?>"><?php echo isset($phone['isValid']) && $phone['isValid'] === false ? $phone['error'] : ""; ?>
                    </div>
                    <button class="button login__submit">
                        <span class="button__text">Kayit Ol</span>
                        <i class="button__icon fas fa-chevron-right"></i>
                    </button>
                </form>
                <div class="text">
                    <h3>Kayit Ol</h3>
                </div>
            </div>
            <div class="screen__background">
                <span class="screen__background__shape screen__background__shape4"></span>
                <span class="screen__background__shape screen__background__shape3"></span>
                <span class="screen__background__shape screen__background__shape2"></span>
                <span class="screen__background__shape screen__background__shape1"></span>
            </div>
        </div>
    </div>
</body>

</html>