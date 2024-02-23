<?php

namespace Api\Subs;

use DateTime;
use Api\BaseController;
use Api\Post\PostController;
use Api\Response;
use PDO;
use Api\Helper;
use Api\Database;
use Api\User\UserController;

class CommentController extends BaseController
{

    public $db;
    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }
    public function createGuest()
    {
        $data = $this->getPost();
        if (!isset($data["name"]) || !isset($data["email"])) {
            Response::json(false, 'Hata oluştu! HATA: Eksik bilgi!', 'name veya email eksik', 404);
        }
        $checker = $this->checkGuestWithEmail($data["email"]);
        if ($checker) {
            Response::json(false, "Kayıtlı Email Zaten Mevcut", $data["email"], 406);
        }
        $sql = "INSERT INTO guest (name, email, ip, is_active,is_banned,updated_at) VALUES (:name, :email, :ip, :is_active, :is_banned, NOW() )";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":name", $data["name"], PDO::PARAM_STR);
        $stmt->bindValue(":email", $data['email'], PDO::PARAM_STR);
        $stmt->bindValue(":ip", $data["ip"], PDO::PARAM_STR);
        $stmt->bindValue(":is_active", 0, PDO::PARAM_INT);
        $stmt->bindValue(":is_banned", 0, PDO::PARAM_INT);


        if (!$stmt->execute()) {

            Response::json(false, 'Hata oluştu! Hata:' . $stmt->errorInfo()[2], '', 404);
        } else {
            $sql = "INSERT INTO subs (email, is_active,updated_at) VALUES ( :email, :is_active,NOW() )";

            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(":email", $data['email'], PDO::PARAM_STR);
            $stmt->bindValue(":is_active", 1, PDO::PARAM_INT);
            if (!$stmt->execute()) {
                Response::json(false, "Subs Oluşturulamadı! Guest oluşturuldu!" . $stmt->errorInfo()[2], "", 400);
            }
        }
        Response::json(true, 'Guest and Subs basariyla oluşturuldu! ID: ' . $this->db->lastInsertId(), $data, 200);
    }
    public function getGuests()
    {

        $sql = "SELECT *, (SELECT COUNT(*) FROM guest) as total FROM guest";

        $stmt = $this->db->prepare($sql);

        if ($stmt->execute()) {
            $data = [];

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                $data[] = $row;
            }
            if ($data == []) {
                Response::json(false, 'Tum Guests Getirilemedi!', $data);
            } else {
                Response::json(true, 'Tum Guests Getirildi!', $data, 200);
            }
        } else {
            $errorInfo = $stmt->errorInfo();
            Response::json(true, 'Tum Guests Getirlemedi! Hata: ' . $errorInfo[2], '', 404);
        }
    }
    public function getGuest($params)
    {

        $sql = "SELECT * FROM guest WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":id", $params["id"], PDO::PARAM_INT);
        if (!$stmt->execute()) {
            $errorInfo = $stmt->errorInfo();
            Response::json(false, 'Guest Getirilemedi! Hata: ' . $errorInfo[2], '', 404);
        }
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if (empty($data["id"])) {
            Response::json(false, "Guest Bulunamadı! ID: " . $params["id"], "", 404);
        }

        Response::json(true, "Guest Getirildi!", $data, 200);
    }
    public function checkGuestWithEmail($email)
    {
        $sql = "SELECT * FROM  guest WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":email", $email, PDO::PARAM_INT);
        $stmt->execute();
        $checker = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($checker == null) {
            return false;
        } else {
            return true;
        }
    }
    public function checkGuest($id)
    {
        $sql = "SELECT * FROM  guest WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        $checker = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($checker == null) {
            return false;
        } else {
            return $checker;
        }
    }
    public function deleteGuest(array $params)
    {
        $this->protect();
        if (!isset($params['id']) || empty($params['id']) || !is_numeric($params['id'])) {
            Response::json(false, 'Id Giriniz!', '', 404);
        }
        $checker = $this->checkGuest($params["id"]);
        if (!$checker) {
            Response::json(false, "Guest Bulunamadı!", $params["id"], 404);
        }
        $sql = "DELETE FROM guest WHERE id = :id";
        $stmt = $this->db->prepare($sql);

        $stmt->bindValue(":id", $params['id'], PDO::PARAM_INT);
        if (!$stmt->execute()) {
            Response::json(false, 'Hata oluştu! Hata:' . $stmt->errorInfo()[2], '', 404);
        }

        Response::json(true, 'Guest silindi! UserID: ' . $params['id']);
    }
    public function updateGuest($params)
    {

        $this->protect();
        if (!isset($params['id']) || empty($params['id']) || !is_numeric($params['id'])) {
            Response::json(false, 'Id Giriniz!', '', 404);
        }
        $check = $this->checkGuest($params['id']);
        if (!$check) {
            Response::json(false, 'Guest not found.', "ID" . $params["id"], 404);
        }
        $data = $this->getPost();
        if (!isset($data) || empty($data)) {
            Response::json(false, "Değiştirecek Bir şey bulunamadı!", "", 404);
        }
        if (isset($data["email"])) {
            $emailCheck = $this->checkGuestWithEmail($data["email"]);
            if ($emailCheck) {
                Response::json(false, "Kayıtlı Email Zaten Var!", $data["email"], 400);
            }
        }

        $name = $email = $ip = $is_active = $is_banned = null;

        foreach (['name', "email", "ip", "is_active", "is_banned"] as $field) {
            if (isset($data[$field])) {
                $$field = $data[$field];
            } elseif (isset($check[$field])) {
                $$field = $check[$field];
            }
        }

        $sql = "UPDATE guest SET name = :name, email = :email, ip = :ip , is_active = :is_active, is_banned= :is_banned, updated_at = NOW() WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":name", $name, PDO::PARAM_STR);
        $stmt->bindValue(":email", $email, PDO::PARAM_STR);
        $stmt->bindValue(":ip", $ip, PDO::PARAM_STR);
        $stmt->bindValue(":is_active", $is_active, PDO::PARAM_INT);
        $stmt->bindValue(":is_banned", $is_banned, PDO::PARAM_INT);

        $stmt->bindValue(":id", $params['id'], PDO::PARAM_INT);

        if (!$stmt->execute()) {
            Response::json(false, 'Hata oluştu! Hata:' . $stmt->errorInfo()[2], '', 500);
        }
        Response::json(true, 'Guest güncellendi. ID: ' . $params['id'], $data);
    }
    public function deleteSubs($params)
    {
        if (!isset($params['id']) || empty($params['id']) || !is_numeric($params['id'])) {
            Response::json(false, 'Id Giriniz!', '', 404);
        }
        $checker = $this->checSubs($params["id"]);
        if (!$checker) {
            Response::json(false, "Subs Bulunamadı!", $params["id"], 400);
        }
        $sql = "DELETE FROM subs WHERE id = :id";
        $stmt = $this->db->prepare($sql);

        $stmt->bindValue(":id", $params['id'], PDO::PARAM_INT);
        if (!$stmt->execute()) {
            Response::json(false, 'Hata oluştu! Hata:' . $stmt->errorInfo()[2], '', 404);
        }

        Response::json(true, 'Subs silindi! UserID: ' . $params['id']);
    }
    public function checSubs($id)
    {
        $sql = "SELECT * FROM  subs WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        $checker = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($checker == null) {
            return false;
        } else {
            return $checker;
        }
    }
    public function createComment($params)
    {
        $data = $this->getPost();
        if (!isset($data["message"]) || !isset($data["guest_id"])) {
            Response::json(false, 'Hata oluştu! HATA: Eksik bilgi!', 'message veya guest_id eksik', 404);
        }
        $checker = new PostController();
        $checker = $checker->checkPostByID($params["id"]);
        if (!$checker) {
            Response::json(false, "Kayıtlı Post Bulunamadı! ", "ID:" . $params["id"], 404);
        }
        $sql = "INSERT INTO comments (guest_id, post_id, is_active,message,likes, updated_at) VALUES (:guest_id, :post_id, :is_active, :message,:likes, NOW() )";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":guest_id", $data["guest_id"], PDO::PARAM_INT);
        $stmt->bindValue(":post_id", $params['id'], PDO::PARAM_INT);
        $stmt->bindValue(":is_active", 0, PDO::PARAM_INT);
        $stmt->bindValue(":message", $data["message"], PDO::PARAM_STR);
        $stmt->bindValue(":likes", 0, PDO::PARAM_INT);


        if (!$stmt->execute()) {

            Response::json(false, 'Hata oluştu! Hata:' . $stmt->errorInfo()[2], '', 404);
        }
        Response::json(true, 'Comment basariyla oluşturuldu! ID: ' . $this->db->lastInsertId(), $data, 200);
    }
    public function updateComments($params)
    {

        $this->protect();
        if (!isset($params['id']) || empty($params['id']) || !is_numeric($params['id'])) {
            Response::json(false, 'Id Giriniz!', '', 404);
        }
        $check = $this->checkComment($params['id']);
        if (!$check) {
            Response::json(false, 'Comment not found.', "ID" . $params["id"], 404);
        }

        $data = $this->getPost();
        if (!isset($data) || empty($data)) {
            Response::json(false, "Değiştirecek Bir şey bulunamadı!", "", 404);
        }

        $guest_id = $post_id = $is_active  = $message = $likes = null;

        foreach (['guest_id', "post_id", "message", "is_active", "likes"] as $field) {
            if (isset($data[$field])) {
                $$field = $data[$field];
            } elseif (isset($check[$field])) {
                $$field = $check[$field];
            }
        }

        $sql = "UPDATE comments SET guest_id = :guest_id, post_id = :post_id, message = :message , is_active = :is_active, likes= :likes, updated_at = NOW() WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":guest_id", $guest_id, PDO::PARAM_INT);
        $stmt->bindValue(":post_id", $post_id, PDO::PARAM_INT);
        $stmt->bindValue(":is_active", $is_active, PDO::PARAM_INT);
        $stmt->bindValue(":message", $message, PDO::PARAM_INT);
        $stmt->bindValue(":likes", $likes, PDO::PARAM_INT);

        $stmt->bindValue(":id", $params['id'], PDO::PARAM_INT);

        if (!$stmt->execute()) {
            Response::json(false, 'Hata oluştu! Hata:' . $stmt->errorInfo()[2], '', 500);
        }
        Response::json(true, 'Comment güncellendi. ID: ' . $params['id'], $data);
    }
    public function checkComment($id)
    {
        $sql = "SELECT * FROM  comments WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        $checker = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($checker == null) {
            return false;
        } else {
            return $checker;
        }
    }
    public function deleteComments($params)
    {
        if (!isset($params['id']) || empty($params['id']) || !is_numeric($params['id'])) {
            Response::json(false, 'Id Giriniz!', '', 404);
        }
        $checker = $this->checkComment($params["id"]);
        if (!$checker) {
            Response::json(false, "Comment Bulunamadı!", $params["id"], 400);
        }
        $sql = "DELETE FROM comments WHERE id = :id";
        $stmt = $this->db->prepare($sql);

        $stmt->bindValue(":id", $params['id'], PDO::PARAM_INT);
        if (!$stmt->execute()) {
            Response::json(false, 'Hata oluştu! Hata:' . $stmt->errorInfo()[2], '', 404);
        }

        Response::json(true, 'Comment silindi! UserID: ' . $params['id']);
    }
    public function getComments()
    {

        $sql = "SELECT *,(SELECT COUNT(*) FROM comments) as total FROM comments";

        $stmt = $this->db->prepare($sql);

        if ($stmt->execute()) {
            $data = [];

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                $data[] = $row;
            }
            if ($data == []) {
                Response::json(false, 'Tum Comments Getirilemedi!', $data);
            } else {
                Response::json(true, 'Tum Comments Getirildi!', $data, 200);
            }
        } else {
            $errorInfo = $stmt->errorInfo();
            Response::json(true, 'Tum Comments Getirlemedi! Hata: ' . $errorInfo[2], '', 404);
        }
    }
    public function getComment($params)
    {

        $sql = "SELECT * FROM comments WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":id", $params["id"], PDO::PARAM_INT);
        if (!$stmt->execute()) {
            $errorInfo = $stmt->errorInfo();
            Response::json(false, 'Comment Getirilemedi! Hata: ' . $errorInfo[2], '', 404);
        }
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if (empty($data["id"])) {
            Response::json(false, "Comment Bulunamadı! ID: " . $params["id"], "", 404);
        }

        Response::json(true, "Comment Getirildi!", $data, 200);
    }
    public function checSubsWithEmail($email)
    {
        $sql = "SELECT * FROM  subs WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":email", $email, PDO::PARAM_INT);
        $stmt->execute();
        $checker = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($checker == null) {
            return false;
        } else {
            return $checker;
        }
    }
    public function createSubs()
    {
        $data = $this->getPost();
        if (!isset($data["email"])) {
            Response::json(false, 'Hata oluştu! HATA: Eksik bilgi!', 'email eksik', 404);
        }
        $checker = $this->checSubsWithEmail($data["email"]);
        if ($checker) {
            Response::json(false, "Kayıtlı Subs Zaten Mevcut", $data["email"], 406);
        }
        $sql = "INSERT INTO subs ( email,  is_active,updated_at) VALUES (:email, :is_active, NOW() )";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":email", $data['email'], PDO::PARAM_STR);
        $stmt->bindValue(":is_active", 1, PDO::PARAM_INT);


        if (!$stmt->execute()) {

            Response::json(false, 'Hata oluştu! Hata:' . $stmt->errorInfo()[2], '', 404);
        } else {
            $sql = "INSERT INTO subs (email, is_active,updated_at) VALUES ( :email, :is_active,NOW() )";

            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(":email", $data['email'], PDO::PARAM_STR);
            $stmt->bindValue(":is_active", 1, PDO::PARAM_INT);
            if (!$stmt->execute()) {
                Response::json(false, "Subs Oluşturulamadı!" . $stmt->errorInfo()[2], "", 400);
            }
        }
        Response::json(true, ' Subs basariyla oluşturuldu! ID: ' . $this->db->lastInsertId(), $data, 200);
    }
    public function getSubs()
    {
        $this->protect();
        $sql = "SELECT * FROM subs";

        $stmt = $this->db->prepare($sql);

        if ($stmt->execute()) {
            $data = [];

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                $data[] = $row;
            }
            if ($data == []) {
                Response::json(true, 'Tum Subs Getirilemedi!', $data);
            } else {
                Response::json(false, 'Tum Subs Getirildi!', $data, 200);
            }
        } else {
            $errorInfo = $stmt->errorInfo();
            Response::json(true, 'Tum Subs Getirlemedi! Hata: ' . $errorInfo[2], '', 404);
        }
    }
    public function updateSubs($params)
    {

        $this->protect();
        if (!isset($params['id']) || empty($params['id']) || !is_numeric($params['id'])) {
            Response::json(false, 'Id Giriniz!', '', 404);
        }
        $check = $this->checSubs($params['id']);
        if (!$check) {
            Response::json(false, 'Sub not found.', "ID" . $params["id"], 404);
        }

        $data = $this->getPost();
        if (!isset($data) || empty($data)) {
            Response::json(false, "Değiştirecek Bir şey bulunamadı!", "", 404);
        }

        $email = $is_active  = null;

        foreach (['email', "is_active"] as $field) {
            if (isset($data[$field])) {
                $$field = $data[$field];
            } elseif (isset($check[$field])) {
                $$field = $check[$field];
            }
        }

        $sql = "UPDATE subs SET email = :email, is_active = :is_active, updated_at = NOW() WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":email", $email, PDO::PARAM_INT);
        $stmt->bindValue(":is_active", $is_active, PDO::PARAM_INT);

        $stmt->bindValue(":id", $params['id'], PDO::PARAM_INT);

        if (!$stmt->execute()) {
            Response::json(false, 'Hata oluştu! Hata:' . $stmt->errorInfo()[2], '', 500);
        }
        Response::json(true, 'Subs güncellendi. ID: ' . $params['id'], $data);
    }
}
