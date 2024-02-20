<?php
session_start();

if (isset($_SESSION["id"])) {

    if (isset($_GET["delete"])) {
        $url = "localhost/xon_onboarding/CRUD/api/user/" . $_GET["id"];

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json'));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");


        $resp = curl_exec($curl);
        curl_close($curl);


        $resp = json_decode($resp, true);
        if ($resp["status"] == false) {
            echo $resp["message"];
            echo $resp["data"];
            $time_start = microtime(true);
            header("refresh:3; url=index.php");
        } else if ($resp["status"] == true) {
            header("Location: index.php");
        }
    } else if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["username"]) && isset($_POST["email"])) {

        $url = "localhost/xon_onboarding/CRUD/api/updateUser/" . $_POST["id"];


        $curl = curl_init();

        $post = array(
            'username' => $_POST["username"],
            'email' => $_POST["email"],
        );
        $jsonData = json_encode($post);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json'));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");


        $resp = curl_exec($curl);
        curl_close($curl);


        $resp = json_decode($resp, true);
        if ($resp["status"] == false) {
            echo $resp["message"];
            echo $resp["data"];
            $time_start = microtime(true);
            header("refresh:3; url=index.php");
        } else if ($resp["status"] == true) {
            header("Location: index.php");
            echo "success";
        }
    } else {
        $url = "localhost/xon_onboarding/CRUD/api/user/" . $_GET["id"];

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json'));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);


        $resp = curl_exec($curl);
        curl_close($curl);


        $resp = json_decode($resp, true);
        if ($resp["status"] == false) {
            echo $resp["message"];
            echo $resp["data"];
            $time_start = microtime(true);
            header("refresh:3; url=index.php");
        } else if ($resp["status"] == true) {
        }
    }
} else {
    header("signin.php");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <style>
        body {

            background-color: blanchedalmond;
        }

        form {
            position: absolute;
            display: flex;
        }



        .fa-input {
            font-family: FontAwesome, 'Helvetica Neue', Helvetica, Arial, sans-serif;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

</head>

<body>
    <h1 id="title">Hos Geldin <span id="hello-username"></span></h1>
    <form action="" method="post">
        <label for="username">UserName:</label><br>
        <input type="hidden" id="id" name="id" value=""><br><br>
        <input type="text" id="username" name="username" value=""><br><br>
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" value=""><br><br>

        <input id="editProfile" type="submit" class="btn fa-input" value="&#xf24d; Update">

    </form>

    <script>
        $(document).ready(function() {
            $.get("api/user/<?= $_SESSION["id"] ?>", function(data, status) {
                if (status == "success") {
                    $('#username').val(data["data"]["username"]);
                    $("#email").val(data["data"]["email"]);
                    $("#hello-username").text(data["data"]["username"]);

                }
            });
        });

        $("#editProfile").click(function(event) {
            event.preventDefault();
            var userData = {
                username: $("#username").val(),
            };

            $.ajax({
                url: "api/updateUser/39",
                type: "PUT",
                contentType: "application/json",
                data: JSON.stringify(userData),
                success: function(data, status) {
                    console.log(data)
                    $("#hello-username").text(data["data"]);
                }
            });
        });
    </script>
</body>

</html>