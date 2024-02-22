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
            Response::json(true, 'Category basariyla oluşturuldu! ID: ' . $this->db->lastInsertId(), $data, 200);
        }
    }
    public function getGuests()
    {

        $sql = "SELECT * FROM guest";

        $stmt = $this->db->prepare($sql);

        if ($stmt->execute()) {
            $data = [];

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                $data[] = $row;
            }
            if ($data == []) {
                Response::json(true, 'Tum Guests Getirilemedi!', $data);
            } else {
                Response::json(false, 'Tum Guests Getirildi!', $data, 200);
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
}
