<?php

namespace Api\Post;

use DateTime;
use Api\BaseController;
use Api\Response;
use Api\Helper;
use PDO;
use Api\Database;


class PostController extends BaseController
{
    public $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function createPost()
    {

        $data = $this->getPost();
        $sql = "INSERT INTO posts (title,slug, details, content, likes, readed,publishing_date,is_active, updated_at) VALUES (:title,:slug, :details, :content, :likes, :readed,:publishing_date,:is_active, NOW())";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":title", $data["title"], PDO::PARAM_STR);
        $stmt->bindValue(":details", $data["details"], PDO::PARAM_STR);
        $stmt->bindValue(":slug", $data["slug"], PDO::PARAM_STR);

        $datetime = DateTime::createFromFormat('d.m.Y', $data["publishing_date"]);
        $mysqlDatetime = $datetime->format('Y-m-d H:i:s');

        $stmt->bindValue(":content", $data["content"], PDO::PARAM_STR);
        $stmt->bindValue(":likes", $data["likes"], PDO::PARAM_INT);
        $stmt->bindValue(":readed", $data["readed"], PDO::PARAM_INT);
        $stmt->bindValue(":publishing_date", $mysqlDatetime, PDO::PARAM_STR);
        $stmt->bindValue(":is_active", $data["is_active"], PDO::PARAM_INT);

        if (!$stmt->execute()) {
            Response::json(false, 'Hata oluştu! Hata:' . $stmt->errorInfo()[2], '', 404);
        } else {
            $post_id = $this->db->lastInsertId();
            $sql = "INSERT INTO user_posts (user_id,post_id,updated_at) VALUES (:user_id,:post_id,NOW())";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(":user_id", $data["user_id"], PDO::PARAM_INT);
            $stmt->bindValue(":post_id", $post_id, PDO::PARAM_INT);
            if (!$stmt->execute()) {
                Response::json(false, "Hata oluştu! Hata: " . $stmt->errorInfo()[2], "User Posts oluşturulamadı!", 404);
            } else {
                Response::json(true, $post_id, $data, 200);
            }
        }
    }
    public function getPostWithID($params)
    {

        $sql = 'SELECT p.*,u.user_id FROM posts as p  LEFT JOIN user_posts as u ON p.id = u.post_id WHERE p.id =:id ';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $params['id'], PDO::PARAM_INT);

        if ($stmt->execute()) {
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if (empty($result)) {
                Response::json(false, 'Post bulunamadi!', $result,  404);
            }
            Response::json(true, 'Post Getirildi!', $result);
        } else {
            $errorInfo = $stmt->errorInfo();
            Response::json(false, 'Post Getirilemedi! Hata: ' . $errorInfo[2], '', 404);
        }
    }
    public function getPostWithSlug($params)
    {
        $sql = 'SELECT p.*,u.user_id FROM posts as p  LEFT JOIN user_posts as u ON p.id = u.post_id WHERE p.slug =:slug ';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':slug', $params['id'], PDO::PARAM_INT);

        if ($stmt->execute()) {
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if (empty($result)) {
                Response::json(false, 'Post bulunamadi!', $result,  404);
            }
            Response::json(true, 'Post Getirildi!', $result);
        } else {
            $errorInfo = $stmt->errorInfo();
            Response::json(false, 'Post Getirilemedi! Hata: ' . $errorInfo[2], '', 404);
        }
    }
    public function uploadPostImage($params)
    {
        $this->getPost();
        if (isset($_FILES['thumbnail'])) {
            $help = new Helper();
            $firstDest =  $help->uploadImage($params['id'], $_FILES['thumbnail'], "thumbnail", "posts");
            Response::json(true, 'Resimler başariyla yüklendi!', $firstDest);
        } else {
            Response::json(false, 'Resim yakalanamadi!', '', 404);
        }
    }
    public function getAllPosts()
    {
        $sql = 'SELECT *, (SELECT COUNT(*) FROM posts) as total, (SELECT SUM(p.readed) FROM posts as p ) as total_readed FROM posts ';
        $stmt = $this->db->prepare($sql);

        if ($stmt->execute()) {
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (empty($result)) {
                Response::json(false, 'Herhangi bir Post bulunamadi!', $result,  404);
            }
            Response::json(true, 'Tum Post Getirildi!', $result);
        } else {
            $errorInfo = $stmt->errorInfo();
            Response::json(false, 'Post Getirilemedi! Hata: ' . $errorInfo[2], '', 404);
        }
    }
    public function getPostWithPage($page)
    {
        $perPage = 5;
        $start = ($page["id"] - 1) * $perPage;

        $sql = 'SELECT COUNT(*) as total_post FROM posts';
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $total = $stmt->fetch(PDO::FETCH_ASSOC)['total_post'];

        $sql = 'SELECT *, (SELECT COUNT(*) FROM posts) as total, (SELECT SUM(p.readed) FROM posts as p ) as total_readed FROM posts  LIMIT :start, :perPage';
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':start', $start, PDO::PARAM_INT);
        $stmt->bindParam(':perPage', $perPage, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        Response::json(true, 'Tüm Gönderiler Getirildi!', [
            'posts' => $result,
            'total_post' => $total,
            'perPage' => $perPage
        ]);
    }

    public function checkPostByID($id)
    {

        $sql = "SELECT p.*, u.user_id FROM posts as p LEFT JOIN user_posts as u ON u.post_id = p.id WHERE p.id=:id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":id", $id, PDO::PARAM_STR);

        $stmt->execute();

        $posts = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($posts == null) {
            return false;
        } else {
            return $posts;
        }
    }
    public function update($params)
    {
        if (!isset($params['id']) || empty($params['id']) || !is_numeric($params['id'])) {
            Response::json(false, 'Id Giriniz!', '', 404);
        }
        $post = $this->checkPostByID($params['id']);
        if (!$post) {
            Response::json(false, 'Posts not found.', "ID" . $params["id"], 404);
            return;
        }


        $data = $this->getPost();

        $title = $details = $content = $likes = $readed = $publishing_date = $is_active = $slug = null;

        foreach (['publishing_date', "title", "details", "content", "likes", "readed", "is_active", "slug"] as $field) {
            if (isset($data[$field])) {
                $$field = $data[$field];
            } elseif (isset($post[$field])) {
                $$field = $post[$field];
            }
        }
        $sql = "UPDATE posts SET title = :title, details = :details,slug = :slug, content = :content , likes = :likes , readed = :readed, publishing_date = :publishing_date, is_active = :is_active, updated_at = NOW() WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":title", $title, PDO::PARAM_STR);
        $stmt->bindValue(":details", $details, PDO::PARAM_STR);
        $stmt->bindValue(":slug", $slug, PDO::PARAM_STR);

        $stmt->bindValue(":content", $content, PDO::PARAM_STR);

        $stmt->bindValue(":likes", $likes, PDO::PARAM_INT);
        $stmt->bindValue(":readed", $readed, PDO::PARAM_INT);
        $stmt->bindValue(":publishing_date", $publishing_date, PDO::PARAM_STR);

        $stmt->bindValue(":is_active", $is_active, PDO::PARAM_INT);
        $stmt->bindValue(":id", $params['id'], PDO::PARAM_INT);

        if (!$stmt->execute()) {
            Response::json(false, 'Hata oluştu! Hata:' . $stmt->errorInfo()[2], '', 500);
        } else {
            if (isset($data["user_id"])) {
                $user_id  = null;
                foreach (["user_id"] as $field) {
                    if (isset($data[$field])) {
                        $$field = $data[$field];
                    } elseif (isset($post[$field])) {
                        $$field = $post[$field];
                    }
                }
                $sql = "UPDATE user_posts SET user_id = :user_id WHERE post_id = :post_id ";
                $stmt = $this->db->prepare($sql);
                $stmt->bindValue(":user_id", $user_id, PDO::PARAM_INT);
                $stmt->bindValue(":post_id", $params["id"], PDO::PARAM_INT);
                if (!$stmt->execute()) {
                    Response::json(false, 'Post güncellenemedi. POSTID: ' . $params['id'], $stmt->errorInfo()[2], 404);
                }
            }
            Response::json(true, 'Post güncellendi. POSTID: ' . $params['id'], $data);
        }
    }
    public function deletePost($params)
    {
        $check = $this->checkPostByID($params["id"]);
        if (!$check) {
            Response::json(false, 'Post Bulunamadı!', 'POSTID' . $params["id"], 404);
        }

        $sql = "DELETE FROM posts WHERE id = :id";

        $stmt = $this->db->prepare($sql);

        $stmt->bindValue(":id", $params['id'], PDO::PARAM_INT);
        if (!$stmt->execute()) {
            Response::json(false, 'Hata oluştu! Hata:' . $stmt->errorInfo()[2], '', 404);
        } else {
            Response::json(true, 'Post silindi! POSTID: ' . $params['id']);
        }
    }
}
