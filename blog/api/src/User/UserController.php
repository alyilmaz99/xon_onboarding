<?php

namespace Api\User;

use Api\BaseController;
use Api\Response;
use PDO;
use Api\Database;


class UserController extends BaseController
{
    public $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function createUser()
    {
        $data = $this->getPost();
        $verification_code = strval(substr(number_format(time() * rand(), 0, '', ''), 0, 6));
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (name, email, username, password, verification_code, is_verified,updated_at) VALUES (:name, :email, :username, :password, :verification_code, :is_verified, NOW())";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":name", $data["name"], PDO::PARAM_STR);
        $stmt->bindValue(":email", $data["email"], PDO::PARAM_STR);

        $stmt->bindValue(":username", $data["username"], PDO::PARAM_STR);
        $stmt->bindValue(":password", $hashedPassword, PDO::PARAM_STR);
        $stmt->bindValue(":verification_code", $verification_code, PDO::PARAM_INT);
        $stmt->bindValue(":is_verified", 0, PDO::PARAM_INT);

        if (!$stmt->execute()) {
            Response::json(false, 'Hata oluştu! Hata:' . $stmt->errorInfo()[2], '', 500);
        } else {
            Response::json(true, 'User Olusturuldu! ', $verification_code);
        }
    }
}
