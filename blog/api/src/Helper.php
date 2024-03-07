<?php

namespace Api;

use PDO;
use Api\Database;
use Api\Upload;

class Helper
{
    public $db;
    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }
    public static function slugify($text, string $divider = '-')
    {

        $text = preg_replace('~[^\pL\d]+~u', $divider, $text);

        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        $text = preg_replace('~[^-\w]+~', '', $text);

        $text = trim($text, $divider);

        $text = preg_replace('~-+~', $divider, $text);

        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }

    public function uploadImage($id, array $file, string $image_name, string $image_table, ?bool $is_user = false)
    {
        $uploadDir = 'uploads/';
        $explode = explode(".", $file["name"]);
        $extension = array_pop($explode);
        $filename = implode(".", $explode);

        $slugify = Helper::slugify($filename);

        $fileNameReal = uniqid() . '_' . $id . "_" . basename($slugify);

        $handle = new Upload($file);
        if ($handle->uploaded) {
            $handle->file_new_name_body = $fileNameReal;
            $handle->image_resize = true;
            $handle->image_convert = 'jpg';
            $handle->image_x = 1920;
            $handle->image_y = 1080;
            $handle->process($uploadDir);

            if ($handle->processed) {
                $destination = $uploadDir . $handle->file_dst_name;
                if ($is_user) {
                    $sql = 'SELECT ' . $image_name . ' FROM ' . $image_table . ' WHERE user_id = :id';
                } else {
                    $sql = 'SELECT ' . $image_name . ' FROM ' . $image_table . ' WHERE id = :id';
                }

                $stmt = $this->db->prepare($sql);
                $stmt->bindValue(":id", $id, PDO::PARAM_STR);
                $stmt->execute();

                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($result) {
                    if (isset($result[$image_name]) && file_exists($result[$image_name])) {
                        unlink($result[$image_name]);
                    }
                } else {
                    unlink($destination);
                    Response::json(false, 'Veri bulunamadi!', '', 404);
                    return;
                }
                if ($is_user) {
                    $sql = 'UPDATE ' . $image_table . ' SET ' . $image_name . ' = :image, updated_at = NOW() WHERE user_id = :id';
                } else {
                    $sql = 'UPDATE ' . $image_table . ' SET ' . $image_name . ' = :image, updated_at = NOW() WHERE id = :id';
                }
                $stmt = $this->db->prepare($sql);
                $stmt->bindValue(":image", $destination, PDO::PARAM_STR);
                $stmt->bindValue(":id", $id, PDO::PARAM_INT);
                $stmt->execute();


                $handle->clean();
                return $destination;
            } else {
                Response::json(false, 'Resim yuklenirken sorun olustu!', $handle->error, 404);
            }
        } else {
            Response::json(false, 'Resim yuklenirken sorun olustu!', 404);
        }
    }
}
