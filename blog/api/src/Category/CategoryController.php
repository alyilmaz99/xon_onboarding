<?php

namespace Api\Category;

use DateTime;
use Api\BaseController;
use Api\Post\PostController;
use Api\Response;
use PDO;
use Api\Helper;
use Api\Database;
use Api\User\UserController;


class CategoryController extends BaseController
{
    public $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }
    public function createCategory()
    {
        $data = $this->getPost();
        if (!isset($data["name"]) || !isset($data["detail"])) {
            Response::json(false, 'Hata oluştu! HATA: Eksik bilgi!', 'name veya detail eksik', 404);
        }
        $sql = "INSERT INTO category (name, detail, created_by, is_active,updated_at) VALUES (:name, :detail, :created_by, :is_active, NOW() )";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":name", $data["name"], PDO::PARAM_STR);
        $stmt->bindValue(":detail", $data['detail'], PDO::PARAM_STR);
        $stmt->bindValue(":created_by", $data["user_id"], PDO::PARAM_STR);
        $stmt->bindValue(":is_active", $data["is_active"], PDO::PARAM_INT);

        if (!$stmt->execute()) {
            Response::json(false, 'Hata oluştu! Hata:' . $stmt->errorInfo()[2], '', 404);
        } else {
            Response::json(true, 'Category basariyla oluşturuldu! ID: ' . $this->db->lastInsertId(), $data, 200);
        }
    }
    public function getCategories()
    {

        $sql = "SELECT *, (SELECT COUNT(*) FROM category) as total FROM category";

        $stmt = $this->db->prepare($sql);

        if ($stmt->execute()) {
            $data = [];

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                $data[] = $row;
            }
            if ($data != []) {
                Response::json(true, 'Tum Categories Getirildi!', $data);
            } else {
                Response::json(false, 'Tum Categories Getirelemedi!', $data, 404);
            }
        } else {
            $errorInfo = $stmt->errorInfo();
            Response::json(false, 'Tum Categories Getirilemedi! Hata: ' . $errorInfo[2], '', 404);
        }
    }
    public function getCategory($params)
    {

        $sql = "SELECT * FROM category WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":id", $params["id"], PDO::PARAM_INT);
        if (!$stmt->execute()) {
            $errorInfo = $stmt->errorInfo();
            Response::json(false, 'Category Getirilemedi! Hata: ' . $errorInfo[2], '', 404);
        }
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if (empty($data["id"])) {
            Response::json(false, "Category Bulunamadı! ID: " . $params["id"], "", 404);
        }

        Response::json(true, "Category Getirildi!", $data, 200);
    }
    public function deleteCategory(array $params)
    {
        if (!isset($params['id']) || empty($params['id']) || !is_numeric($params['id'])) {
            Response::json(false, 'Id Giriniz!', '', 404);
        }

        $sql = "DELETE FROM category WHERE id = :id";
        $stmt = $this->db->prepare($sql);

        $stmt->bindValue(":id", $params['id'], PDO::PARAM_INT);
        if (!$stmt->execute()) {
            Response::json(false, 'Hata oluştu! Hata:' . $stmt->errorInfo()[2], '', 404);
        }
        $check = $this->checkCategory($params['id']);

        if ($check) {
            Response::json(false, 'Category Bulunumadı', $params["id"], 404);
        }
        Response::json(true, 'Category silindi! UserID: ' . $params['id']);
    }

    public function checkCategory($id)
    {
        $sql = "SELECT * FROM CATEGORY WHERE id = :id";
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
    public function updateCategory($params)
    {

        if (!isset($params['id']) || empty($params['id']) || !is_numeric($params['id'])) {
            Response::json(false, 'Id Giriniz!', '', 404);
        }
        $check = $this->checkCategory($params['id']);
        if (!$check) {
            Response::json(false, 'Categories not found.', "ID" . $params["id"], 404);
        }
        $data = $this->getPost();
        if (!isset($data) || empty($data)) {
            Response::json(false, "Değiştirecek Bir şey bulunamadı!", "", 404);
        }

        $name = $detail = $created_by = $is_active = null;

        foreach (['name', "detail", "created_by", "is_active"] as $field) {
            if (isset($data[$field])) {
                $$field = $data[$field];
            } elseif (isset($check[$field])) {
                $$field = $check[$field];
            }
        }
        $sql = "UPDATE category SET name = :name, detail = :detail, created_by = :created_by , is_active = :is_active, updated_at = NOW() WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":name", $name, PDO::PARAM_STR);
        $stmt->bindValue(":detail", $detail, PDO::PARAM_STR);
        $stmt->bindValue(":created_by", $created_by, PDO::PARAM_INT);
        $stmt->bindValue(":is_active", $is_active, PDO::PARAM_INT);
        $stmt->bindValue(":id", $params['id'], PDO::PARAM_INT);

        if (!$stmt->execute()) {
            Response::json(false, 'Hata oluştu! Hata:' . $stmt->errorInfo()[2], '', 500);
        }
        Response::json(true, 'Category güncellendi. ID: ' . $params['id'], $data);
    }
    public function uploadCategoryImage($params)
    {
        $this->getPost();
        if (isset($_FILES['image']) && isset($_FILES['thumbnail'])) {
            $help = new Helper();
            $firstDest =  $help->uploadImage($params['id'], $_FILES['image'], "image", "category");
            $secondDest = $help->uploadImage($params['id'], $_FILES['thumbnail'], "thumbnail", "category");
            $data = [$firstDest, $secondDest];
            Response::json(true, 'Resimler başariyla yüklendi!', $data);
        } else {
            Response::json(false, 'Resim yakalanamadi!', '', 404);
        }
    }
    public function addCategoryPosts($params)
    {
        $data = $this->getPost();
        if (!isset($data["post_id"])) {
            Response::json(false, 'Hata oluştu! HATA: Eksik bilgi!', 'post_id eksik', 404);
        }
        $checker = $this->checkCategory($params["id"]);
        if (!$checker) {
            Response::json(false, 'Category Bulunamadı! ID:' . $params["id"], '', 404);
        }
        $post = new PostController();
        $postChecker = $post->checkPostByID($data["post_id"]);
        if (!$postChecker) {
            Response::json(false, 'Post Bulunamadı! ID:' . $data["post_id"], '', 404);
        }
        $sql = "INSERT INTO category_posts (user_id, category_id, post_id,updated_at) VALUES (:user_id, :category_id, :post_id, NOW() )";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":user_id", $data["user_id"], PDO::PARAM_INT);
        $stmt->bindValue(":category_id", $checker["id"], PDO::PARAM_INT);
        $stmt->bindValue(":post_id", $data["post_id"], PDO::PARAM_INT);

        if (!$stmt->execute()) {
            Response::json(false, 'Hata oluştu! Hata:' . $stmt->errorInfo()[2], '', 404);
        } else {
            Response::json(true, 'Category Post basariyla oluşturuldu! ID: ' . $this->db->lastInsertId(), $data, 200);
        }
    }

    public function checkCategoryPost($id)
    {
        $sql = "SELECT * FROM  category_posts WHERE category_id = :id";
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
    public function updateCategoryPost($params)
    {

        if (!isset($params['id']) || empty($params['id']) || !is_numeric($params['id'])) {
            Response::json(false, 'Id Giriniz!', '', 404);
        }
        $check = $this->checkCategoryPost($params['id']);
        if (!$check) {
            Response::json(false, 'Category found.', "ID" . $params["id"], 404);
        }
        $data = $this->getPost();
        if (!isset($data) || empty($data)) {
            Response::json(false, "Değiştirecek Bir şey bulunamadı!", "", 404);
        }
        if (isset($data["user_id"])) {
            $userChecker = new UserController();
            $userChecker = $userChecker->checkUserById($data["user_id"]);
            if (!$userChecker) {
                Response::json(false, "User Bulunamadı!", "UserID:" . $data["user_id"], 404);
            }
        }
        if (isset($data["post_id"])) {
            $postChecker = new PostController();
            $postChecker = $postChecker->checkPostByID($data["post_id"]);
            if (!$postChecker) {
                Response::json(false, "Post Bulunamadı!", "POSTID: " . $data["post_id"], 404);
            }
        }
        $user_id = $post_id = null;

        foreach (['user_id', "post_id"] as $field) {
            if (isset($data[$field])) {
                $$field = $data[$field];
            } elseif (isset($check[$field])) {
                $$field = $check[$field];
            }
        }

        $sql = "UPDATE category_posts SET user_id = :user_id, post_id = :post_id, updated_at = NOW() WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":user_id", $user_id, PDO::PARAM_INT);
        $stmt->bindValue(":post_id", $post_id, PDO::PARAM_INT);
        $stmt->bindValue(":id", $params['id'], PDO::PARAM_INT);

        if (!$stmt->execute()) {
            Response::json(false, 'Hata oluştu! Hata:' . $stmt->errorInfo()[2], '', 500);
        }
        Response::json(true, 'Category Posts güncellendi. ID: ' . $params['id'], $data);
    }

    public function deleteCategoryPosts(array $params)
    {
        if (!isset($params['id']) || empty($params['id']) || !is_numeric($params['id'])) {
            Response::json(false, 'Id Giriniz!', '', 404);
        }

        $sql = "DELETE FROM category_posts WHERE id = :id";
        $stmt = $this->db->prepare($sql);

        $stmt->bindValue(":id", $params['id'], PDO::PARAM_INT);
        if (!$stmt->execute()) {
            Response::json(false, 'Hata oluştu! Hata:' . $stmt->errorInfo()[2], '', 404);
        }
        $check = $this->checkCategoryPost($params['id']);

        if (!$check) {
            Response::json(false, 'Category Posts Bulunumadı', $params["id"], 404);
        }
        Response::json(true, 'Category Posts silindi! CategoryPostsID: ' . $params['id']);
    }

    public function getCategoryPosts($params)
    {

        $sql = "SELECT * FROM category_posts WHERE category_id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":id", $params["id"], PDO::PARAM_INT);
        if (!$stmt->execute()) {
            $errorInfo = $stmt->errorInfo();
            Response::json(false, 'Category Posts Getirilemedi! Hata: ' . $errorInfo[2], '', 404);
        }
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if (empty($data["id"])) {
            Response::json(false, "Category Posts Bulunamadı! ID: " . $params["id"], "", 404);
        }

        Response::json(true, "Category Posts Getirildi!", $data, 404);
    }
}
