<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <style>
        body {
            background-color: #F5F5F5;
        }

        input:focus {
            border: none;
            outline: none;
        }

        .center {
            font-family: "Audiowide", sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .card {
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 2px 2px 5px lightblue;
            background-color: white;
            height: 50%;
            width: 35%;
            border-radius: 10%;
        }


        .img {
            position: absolute;
            left: 25%;
        }

        .img img {
            padding-top: 2px;
            width: 100%;
            max-width: 348px;
            border-radius: 10%;
        }

        @media only screen and (max-width: 1100px) {
            .card {
                width: 80%;
            }

            .img {
                left: 10%;

            }

            .img img {

                max-width: 348px;

            }

            .form {
                left: 50%
            }
        }

        @media only screen and (max-width: 1700px) {
            .card {
                width: 40%;
            }

            .img {
                left: 30%;

            }

            .img img {

                max-width: 315px;

            }

            .form {
                left: 52%
            }
        }

        form {
            margin-top: 20px;
        }

        .form {

            font-family: "Poppins", sans-serif;
            top: 28%;
            left: 47%;
            position: absolute;
            justify-self: center;
            align-items: center;
        }



        .login-text {
            color: #7A999C;
            font-family: "Poppins", sans-serif;

        }

        input[type=text],
        [type=email] {
            margin-top: 10px;
            width: 200%;
            height: 30px;
            box-sizing: border-box;
            border: none;
            border-bottom: 2px solid #5F7C8D;
        }


        input[type=submit] {
            margin-top: 50px;
            width: 200%;
            height: 40px;
            box-sizing: border-box;
            border: 1px;
            cursor: pointer;
            font-family: "Poppins", sans-serif;
            color: white;
            font-size: 18px;
            background: #4B687A;
            border-radius: 2px;

        }

        label {
            margin-top: 20px;
            display: inline-block;
            font-weight: 300;
            color: #5F7C8D;
        }
    </style>
</head>

<body>
    <div class="center">
        <div class="card">
            <div class="img">
                <img src="assets/images/bg-1.png" alt="login_image">
            </div>
            <div class="form">
                <div class="login-text">
                    <h1>Giriş Yap</h1>
                </div>
                <form action="#">
                    <label for="email">Email</label><br>
                    <input type="email" id="email" name="email"><br>
                    <label for="password">Şifre:</label><br>
                    <input type="text" id="password" name="password"><br><br>
                    <input type="submit" value="Giriş Yap">
                </form>
            </div>
        </div>
    </div>
</body>

</html>