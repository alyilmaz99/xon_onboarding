<?php

namespace Api\Token;

use Api\BaseController;

use Api\Database;
use PDO;
use Api\Response;
use PDOException;

class TokenController extends BaseController
{

    public $db;
    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getToken()
    {
        $data = $this->getPost();
        if (empty($data)) {
            Response::json(true, 'User Bilgisi Giriniz!', $data, 404);
        } else {
            $user_id = $this->getUserByEmail($data["email"]);

            try {
                $password = $data["password"];

                $checkUserQuery = "SELECT * FROM users WHERE email = :email";
                $stmt = $this->db->prepare($checkUserQuery);
                $stmt->bindParam(':email', $data['email'], PDO::PARAM_STR);
                $stmt->execute();

                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$user) {
                    Response::json(false, 'Hata oluştu veya herhangi bir user bulunamadi!', '', 404);
                }

                if (!password_verify($password, $user['password'])) {
                    Response::json(false, 'Hata oluştu veya user sifresi yanlis!', '', 404);
                }

                $isCreated = "SELECT * FROM access_token WHERE user_id = :user_id";
                $stmt = $this->db->prepare($isCreated);
                $stmt->bindParam(":user_id", $user_id, PDO::PARAM_STR);
                $stmt->execute();

                $tokenList = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($tokenList == null) {

                    $token = bin2hex(openssl_random_pseudo_bytes(24));

                    $insertTokenQuery = "INSERT INTO access_token (token,expiry,user_id) VALUES (:token, NOW() + INTERVAL 1 MONTH, :user_id)";
                    $stmt = $this->db->prepare($insertTokenQuery);
                    $stmt->bindParam(':token', $token, PDO::PARAM_STR);
                    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_STR);

                    $stmt->execute();
                    Response::json(true, 'Token olusturuldu!', $token);
                } else {
                    $token = bin2hex(openssl_random_pseudo_bytes(24));

                    $updateTokenQuery = "UPDATE access_token SET token = :token, expiry = NOW() + INTERVAL 1 MONTH, created_at = NOW() WHERE user_id = :user_id";
                    $stmt = $this->db->prepare($updateTokenQuery);
                    $stmt->bindParam(':token', $token, PDO::PARAM_STR);
                    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_STR);
                    $stmt->execute();
                    Response::json(true, 'Token getirildi!', $token);
                }
            } catch (PDOException $e) {
                echo "Veritabani Hatasi: " . $e->getMessage();
                return 'gateway hatasi';
            }
        }
    }

    public function tokenControl(array $data): bool
    {
        $isCreated = "SELECT * FROM access_token WHERE token = :token";
        $stmt = $this->db->prepare($isCreated);
        $stmt->bindParam(":token", $data['token'], PDO::PARAM_STR);
        $stmt->execute();
        $tokenList = $stmt->fetch(PDO::FETCH_ASSOC);
        if (empty($tokenList)) {
            return false;
        } else {
            $now = date('Y-m-d H:i:s');
            if ($tokenList['expiry'] > $now) {
                return true;
            } else {
                return false;
            }
        }
    }
    public function getUserByEmail($email)
    {
        $sql = "SELECT * FROM users WHERE email=:email";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);

        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user != null) {
            return $user['id'];
        } else {
            return null;
        }
    }
    public function onlyGetToken($id): string
    {

        $token = bin2hex(openssl_random_pseudo_bytes(24));

        $insertTokenQuery = "INSERT INTO access_token (token,expiry,user_id) VALUES (:token, NOW() + INTERVAL 1 MONTH, :user_id)";
        $stmt = $this->db->prepare($insertTokenQuery);
        $stmt->bindParam(':token', $token, PDO::PARAM_STR);
        $stmt->bindParam(':user_id', $id, PDO::PARAM_STR);

        if ($stmt->execute()) {
            return $token;
        }

        return false;
    }
}
