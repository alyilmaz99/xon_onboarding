<?php
include_once("Database.php");
class UserController
{
    public $db;
    public function __construct()
    {
        DB::Init();
        $this->db = DB::get();
    }

    public function createUser()
    {
        $helper = new Helper();
        $response = new Response();
        $data = $helper->getPost();
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("sss", $data["username"], $data["email"], $data["password"]);
        if ($stmt->execute()) {
            $response->json(true, "User Created!", $data["email"], 200);
        } else {
            $response->json(false, "ERROR while creating user!", $stmt->error, 404);
        }
    }

    public function deleteUser($params)
    {

        $response = new Response();

        $sql = "DELETE FROM users WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("s", $params['id']);
        if ($stmt->execute()) {
            $response->json(true, "User deleted successfully!", null, 200);
        } else {
            $response->json(false, "Error while deleting user!", $stmt->error, 404);
        }
    }
    public function getUser($params)
    {
        $response = new Response();

        $sql = "SELECT * FROM users WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("s", $params["id"]);
        if ($stmt->execute()) {
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();
                $response->json(true, "User getirildi!", $user, 200);
            } else {
                $response->json(false, "no user", 404);
            }
        } else {
            $response->json(false, "ERROR", $stmt->error, 404);
        }
    }
    public function getAllUser()
    {
        $response = new Response();

        $sql = "SELECT * FROM users ";
        $stmt = $this->db->prepare($sql);

        if ($stmt->execute()) {
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $user = $result->fetch_all();
                $response->json(true, "Success!", $user, 200);
            } else {
                $response->json(false, "no user", 404);
            }
        } else {
            $response->json(false, "ERROR", $stmt->error, 404);
        }
    }

    public function updateUser($params)
    {
        $response = new Response();
        $helper = new Helper();
        $data = $helper->getPost();
        $sql = "UPDATE users SET username = ? WHERE id = ? ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ss", $data["username"], $params["id"]);
        if ($stmt->execute()) {
            $response->json(true, "User updated!", $data["username"], 200);
        } else {
            $response->json(false, "ERROR!", $data["username"], 404);
        }
    }
}
