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
        $sql = "INSERT INTO users (name, email, username, password, verification_code, is_verified,permissions,updated_at) VALUES (:name, :email, :username, :password, :verification_code, :is_verified,:permissions, NOW())";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":name", $data["name"], PDO::PARAM_STR);
        $stmt->bindValue(":email", $data["email"], PDO::PARAM_STR);

        $stmt->bindValue(":username", $data["username"], PDO::PARAM_STR);
        $stmt->bindValue(":password", $hashedPassword, PDO::PARAM_STR);
        $stmt->bindValue(":verification_code", $verification_code, PDO::PARAM_INT);
        $stmt->bindValue(":is_verified", 0, PDO::PARAM_INT);
        $stmt->bindValue(":permissions", 1, PDO::PARAM_INT);

        if (!$stmt->execute()) {
            Response::json(false, 'Hata oluştu! Hata:' . $stmt->errorInfo()[2], '', 500);
        } else {
            Response::json(true, 'User Olusturuldu! ', $verification_code);
        }
    }
    public function getAll()
    {

        $sql = "SELECT * FROM users";
        $stmt = $this->db->query($sql);
        $data = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

            $data[] = $row;
        }
        if ($data == []) {
            Response::json(false, 'Hata oluştu veya herhangi bir user bulunamadi!', '', 404);
        } else {
            Response::json(true, 'Tum userlar getirildi!', $data);
        }
    }
    public function getUser($params)
    {
        $sql = "SELECT * FROM users WHERE id=:id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":id", $params['id'], PDO::PARAM_INT);

        if (!$stmt->execute()) {
            Response::json(false, 'Hata oluştu! Hata:' . $stmt->errorInfo()[2], '', 500);
        } else {
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            Response::json(true, 'User getirildi!', $data);
        }
    }
    public function updateUser($params)
    {
        $this->protect();
        if (!isset($params['id']) || empty($params['id']) || !is_numeric($params['id'])) {
            Response::json(false, 'Id Giriniz!', '', 404);
        }
        $check = $this->checkUserById($params['id']);
        if ($check) {
            $user = $this->checkUser($params["id"]);
            $data = $this->getPost();

            $name = $data["name"] ?? null;
            $email = $data["email"] ?? null;
            $is_verified = $data["is_verified"] ?? null;
            $permissions = $data["permissions"] ?? null;
            if (!isset($name)) {
                $name = $user['name'];
            }
            if (!isset($email)) {
                $email = $user['email'];
            }
            if (!isset($is_verified)) {
                $is_verified = $user['is_verified'];
            }
            if (!isset($permissions)) {
                $permissions = $user['permissions'];
            }

            $sql = "UPDATE users SET name = :name, email = :email, permissions = :permissions , is_verified = :is_verified WHERE id = :id";

            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(":name", $name, PDO::PARAM_STR);
            $stmt->bindValue(":email", $email, PDO::PARAM_STR);
            $stmt->bindValue(":is_verified", $is_verified, PDO::PARAM_STR);

            $stmt->bindValue(":permissions", $permissions, PDO::PARAM_STR);
            $stmt->bindValue(":id", $params['id'], PDO::PARAM_INT);

            if (!$stmt->execute()) {
                Response::json(false, 'Hata oluştu! Hata:' . $stmt->errorInfo()[2], '', 500);
            } else {
                Response::json(true, 'Profiliniz güncellendi. UserID: ' . $params['id'], $data);
            }
        } else {
            Response::json(false, 'ID: ' . $params['id'] . ' user bulunmamakta!', '', 404);
        }
    }
    public function checkUserById($id)
    {

        $sql = "SELECT * FROM users WHERE id=:id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":id", $id, PDO::PARAM_STR);

        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user == null) {
            return false;
        } else {
            return true;
        }
    }
    public function checkUser($id)
    {
        $data = $this->getPost();

        $sql = "SELECT * FROM users WHERE id=:id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":id", $id, PDO::PARAM_STR);

        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user == null) {
            return false;
        } else {
            return $user;
        }
    }
    public function deleteUser(array $params)
    {
        $this->protect();
        if (!isset($params['id']) || empty($params['id']) || !is_numeric($params['id'])) {
            Response::json(false, 'Id Giriniz!', '', 404);
        }
        $check = $this->checkUserById($params['id']);

        if ($check) {
            $sql = "DELETE FROM users WHERE id = :id";
            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(":id", $params['id'], PDO::PARAM_INT);
            if (!$stmt->execute()) {
                Response::json(false, 'Hata oluştu! Hata:' . $stmt->errorInfo()[2], '', 404);
            } else {
                Response::json(true, 'Profiliniz silindi! UserID: ' . $params['id']);
            }
        } else {
            Response::json(false, 'ID: ' . $params['id'] . ' user bulunmamakta!', '', 404);
        }
    }
}
