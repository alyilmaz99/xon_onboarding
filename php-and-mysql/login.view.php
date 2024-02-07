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
                        <input type="email" id="email" name="email" class="login__input" placeholder="Email">
                    </div>

                    <button class="button login__submit" id="loginButton">
                        <span class="button__text">Login</span>
                        <i class="button__icon fas fa-chevron-right"></i>
                    </button>

                </form>
                <div style="display: flex; justify-content: center">
                    <p>
                        <a href="signup.view.php">Kayit Ol</a>
                    </p>
                </div>
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

<script>
document.getElementById("kayitol").addEventListener("click", function() {
    window.location.href = 'signup.view.php';
});
</script>
