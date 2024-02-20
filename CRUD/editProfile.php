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
        }
    } else if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["username"]) && isset($_POST["email"])) {

        $url = "localhost/xon_onboarding/CRUD/api/updateUser/" . $_SESSION["id"];


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

</head>

<body>
    <h1>Hos Geldin <?= $resp["data"]["username"]; ?></h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="username">UserName:</label><br>
        <input type="text" id="username" name="username" value="<?= $resp["data"]["username"]; ?>"><br><br>
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" value="<?= $resp["data"]["email"]; ?>"><br><br>

        <input type="submit" class="btn fa-input" value="&#xf24d; Update">

    </form>
</body>

</html>