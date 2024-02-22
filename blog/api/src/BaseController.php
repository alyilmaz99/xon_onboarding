<?php

namespace Api;

use PDO;

class BaseController
{
    public function getAuthorizeTokenFromRequest()
    {
        $headers = apache_request_headers();
        if (isset($headers["token"]) && !empty(trim($headers["token"]))) {
            return $headers["token"];
        }

        if (isset($headers["Token"]) && !empty(trim($headers["Token"]))) {
            return $headers["Token"];
        }

        return null;
    }
    public function getAuthorizedUser(): ?array
    {
        $token = $this->getAuthorizeTokenFromRequest();
        if (!isset($token)) {
            return null;
        }

        $db = Database::getInstance()->getConnection();

        $sql = "SELECT u.* FROM access_token as a LEFT JOIN users as u on u.id = a.user_id WHERE a.token = :token AND a.expiry > CURRENT_TIMESTAMP()";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(":token", $token, PDO::PARAM_STR);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!isset($data)) {
            return null;
        } else if ($data == false) {
            return null;
        }
        return $data;
    }
    public function getPost()
    {
        return json_decode(file_get_contents('php://input'), true);
    }

    public function protect(): ?array
    {
        $user = $this->getAuthorizedUser();

        if (!isset($user)) {
            http_response_code(401);
            Response::json(false, 'Token Gecersiz', '', 404);
            return null;
        }

        return $user;
    }
}
