<?php

include_once 'Validator.php';
$validator = Validator::getInstance();

if (isset($_POST["username"]) && isset($_POST["email"]) && isset($_POST["password"])) {
    $username = $validator->textValidation($_POST['username']);
    $email = $validator->emailValidation($_POST["email"]);

    $username = $_POST['username'];
    $passwordHash = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $email = $_POST['email'];


    $post = array(
        'username' => $username,
        'password' => $passwordHash,
        'email' => $email,
    );
    $jsonData = json_encode($post);


    $url = "localhost/xon_onboarding/CRUD/api/user";

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
        header("Location: signin.php");
    }
}
