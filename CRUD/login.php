<?php
session_start();
if (isset($_POST["email"]) && isset($_POST["password"])) {
    $email = $_POST['email'];

    $post = array(
        'email' => $email,
    );
    $jsonData = json_encode($post);


    $url = "localhost/xon_onboarding/CRUD/api/login";

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json'));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);

    $resp = curl_exec($curl);
    curl_close($curl);
    $time_start = microtime(true);

    $resp = json_decode($resp, true);
    if ($resp["status"] == false) {
        echo $resp["message"];
        echo $resp["data"];
        $time_start = microtime(true);
        header("refresh:3; url=signup.php");
    } else if ($resp["status"] == true) {
        if (password_verify($_POST["password"], $resp["data"][0][3])) {
            $_SESSION["email"] = $resp["data"][0][2];
            $_SESSION["id"] = $resp["data"][0][0];
            header("Location: index.php");
            exit();
        } else {
            echo "wrong password";
            header("refresh:3; url=signin.php ");
        }
    }
}
