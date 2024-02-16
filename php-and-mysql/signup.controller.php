<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require 'vendor/autoload.php';

include_once 'Database.php';

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class SignUpController
{
    public $db;
    public function __construct()
    {
        $database = Database::Initialize("localhost", "todo", "root", "Ali.2901");

        $this->db = Database::getInstance()->getConnection();
    }
    public function signUp($name, $email, $password, $phone)
    {
        $verification_code = strval(substr(number_format(time() * rand(), 0, '', ''), 0, 6));
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO user (name, email, password, phone, verification_code, updated_at)
        VALUES (:name, :email, :password, :phone, :verification_code, NOW())";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":name", $name, PDO::PARAM_INT);
        $stmt->bindValue(":email", $email, PDO::PARAM_STR);
        $stmt->bindValue(":password", $hashedPassword, PDO::PARAM_STR);
        $stmt->bindValue(":phone", $phone, PDO::PARAM_STR);
        $stmt->bindValue(":verification_code", $verification_code, PDO::PARAM_STR);

        if ($stmt->execute()) {
            $email = $this->emailSend($email, $name, $verification_code);
            if ($email) {
                echo $email;
            }
        }
    }

    public function emailSend(string $email, string $name, string $message)
    {
        $link = "http://localhost/xon_onboarding/php-and-mysql/verify.view.php?verification_code=" . $message . "&email=" . $email;

        $mail = new PHPMailer(true);
        try {
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host = 'mail.15numara.com.tr';
            $mail->Port = 587;
            $mail->SMTPAuth = true;
            $mail->Username = 'test@15numara.com.tr';
            $mail->Password = 'Ali.2901';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->setFrom('test@15numara.com.tr', 'TODO-ALY-PROJECT-XON-TECH');
            $mail->addAddress($email, $name);
            $mail->isHTML(true);
            $mail->Subject = "TODO PROJECT";
            $mail->Body = '<p style="font-size: 20px;">Sayin: '
                . $name . '<br><br>' . $message .
                $link . '</p>';
            $mail->send();
            return $message;
        } catch (Exception $e) {
            return false;
        }
    }
}
