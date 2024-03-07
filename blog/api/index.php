<?php

use Api\Category\CategoryController;
use Api\Database;
use Api\User\UserController;
use Api\ErrorHandler;
use Api\Post\PostController;
use Api\Subs\CommentController;
use Api\Token\TokenController;

require_once 'vendor/autoload.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
header("Content-type: application/json; charset=UTF-8");

const BASE_DIR = '/xon_onboarding/blog/api/';

$parts = explode("/", $_SERVER["REQUEST_URI"]);


$database = Database::Initialize("localhost", "blog", "root", "Ali.2901");

$request_uri = $_SERVER['REQUEST_URI'];

if (substr($request_uri, 0, strlen(BASE_DIR)) === BASE_DIR) {
    $request_uri = substr($request_uri, strlen(BASE_DIR));
}

function api(string $method, string $path, $handler)
{
    global $request_uri;
    if (strtoupper($method) !== strtoupper($_SERVER['REQUEST_METHOD'])) {
        return;
    }

    $regex = preg_replace('/:[a-zA-Z0-9_]+/', '([a-zA-Z0-9_]+)', $path);
    $regex = '/^' . str_replace('/', '\/', $regex) . '$/';

    $exploded = explode('/', $path);
    $parameters = array_filter($exploded, function ($item) {
        return substr($item, 0, 1) === ':';
    });
    $parameters = array_map(function ($item) {
        return substr($item, 1);
    }, $parameters);

    if (preg_match($regex, $request_uri, $matches)) {
        array_shift($matches);
        $parameters = array_combine($parameters, $matches);

        if (!is_callable($handler)) {
            http_response_code(500);
            die();
        }

        $handler($parameters);
        die();
    }
}
api('POST', 'user', function () {
    $controller = new UserController();
    $controller->createUser();
});

api('GET', 'user', function () {
    $controller = new UserController();
    $controller->getAll();
});
api('GET', 'user/:id', function ($params) {
    $controller = new UserController();
    $controller->getUser($params);
});
api('PUT', 'user/:id', function ($params) {
    $controller = new UserController();
    $controller->updateUser($params);
});
api('DELETE', 'user/:id', function ($params) {
    $controller = new UserController();
    $controller->deleteUser($params);
});
api("POST", "token", function () {
    $controller = new TokenController();
    $controller->getToken();
});
api("POST", "post", function () {
    $controller = new PostController();
    $controller->createPost();
});
api("POST", "post/image/:id", function ($params) {
    $controller = new PostController();
    $controller->uploadPostImage($params);
});
api("GET", "post/:id", function ($params) {
    $controller = new PostController();
    $controller->getPostWithID($params);
});
api("GET", "post/page/:id", function ($params) {
    $controller = new PostController();
    $controller->getPostWithPage($params);
});
api("GET", "post", function () {
    $controller = new PostController();
    $controller->getAllPosts();
});
api("GET", "postWithSlug/:id", function ($slug) {
    $controller = new PostController();
    $controller->getPostWithSlug($slug);
});
api("PUT", "post/:id", function ($params) {
    $controller = new PostController();
    $controller->update($params);
});
api("DELETE", "post/:id", function ($params) {
    $controller = new PostController();
    $controller->deletePost($params);
});
api("POST", "category", function () {
    $controller = new CategoryController();
    $controller->createCategory();
});
api("GET", "category", function () {
    $controller = new CategoryController();
    $controller->getCategories();
});
api("GET", "category/:id", function ($params) {
    $controller = new CategoryController();
    $controller->getCategory($params);
});
api("DELETE", "category/:id", function ($params) {
    $controller = new CategoryController();
    $controller->deleteCategory($params);
});
api("PUT", "category/:id", function ($params) {
    $controller = new CategoryController();
    $controller->updateCategory($params);
});
api("POST", "category/image/:id", function ($params) {
    $controller = new CategoryController();
    $controller->uploadCategoryImage($params);
});
api("POST", "category/post/:id", function ($params) {
    $controller = new CategoryController();
    $controller->addCategoryPosts($params);
});
api("PUT", "category/post/:id", function ($params) {
    $controller = new CategoryController();
    $controller->updateCategoryPost($params);
});
api("DELETE", "category/post/:id", function ($params) {
    $controller = new CategoryController();
    $controller->deleteCategoryPosts($params);
});
api("GET", "category/post/:id", function ($params) {
    $controller = new CategoryController();
    $controller->getCategoryPosts($params);
});
api("POST", "guest", function () {
    $controller = new CommentController();
    $controller->createGuest();
});
api("GET", "guest", function () {
    $controller = new CommentController();
    $controller->getGuests();
});
api("GET", "guest/:id", function ($params) {
    $controller = new CommentController();
    $controller->getGuest($params);
});
api("DELETE", "guest/:id", function ($params) {
    $controller = new CommentController();
    $controller->deleteGuest($params);
});
api("PUT", "guest/:id", function ($params) {
    $controller = new CommentController();
    $controller->updateGuest($params);
});
api("DELETE", "subs/:id", function ($params) {
    $controller = new CommentController();
    $controller->deleteSubs($params);
});
api("POST", "subs", function () {
    $controller = new CommentController();
    $controller->createSubs();
});
api("PUT", "subs/:id", function ($params) {
    $controller = new CommentController();
    $controller->updateSubs($params);
});
api("GET", "subs", function () {
    $controller = new CommentController();
    $controller->getSubs();
});
api("POST", "comment/:id", function ($params) {
    $controller = new CommentController();
    $controller->createComment($params);
});

api("PUT", "comment/:id", function ($params) {
    $controller = new CommentController();
    $controller->updateComments($params);
});


api("DELETE", "comment/:id", function ($params) {
    $controller = new CommentController();
    $controller->deleteComments($params);
});
api("GET", "comment", function () {
    $controller = new CommentController();
    $controller->getComments();
});
api("GET", "comment/:id", function ($params) {
    $controller = new CommentController();
    $controller->getComment($params);
});

api("POST", "user/upload/:id", function ($params) {
    $controller = new UserController();
    $controller->uploadUserImage($params);
});
http_response_code(404);

die();
