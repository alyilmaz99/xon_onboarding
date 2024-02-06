<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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
                <form class="login" action="login.controller.php" method="post">

                    <div class="login__field">
                        <input type="password" id="password" name="password" class="login__input"
                            placeholder="Password">
                    </div>
                    <div class="login__field">
                        <input type="email" id="email" name="email" class="login__input"
                            placeholder="<?php echo isset($email['data']) ? $email['data'] : 'E-Mail'; ?>"><?php echo isset($mail['isValid']) && $mail['isValid'] === false ? $mail['error'] : ""; ?>
                    </div>

                    <button class="button login__submit">
                        <span class="button__text">Login</span>
                        <i class="button__icon fas fa-chevron-right"></i>
                    </button>
                    <button class="button login__submit" onclick="<?php header("Location: signup.view.php");  ?>">
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