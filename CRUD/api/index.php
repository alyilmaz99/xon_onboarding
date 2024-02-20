<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);
include_once "./Database.php";
include_once "Reponse.php";
include_once "UserController.php";
include_once "Helper.php";
header("Content-type: application/json; charset=UTF-8");

const BASE_DIR = '/xon_onboarding/CRUD/api/';


$parts = explode("/", $_SERVER["REQUEST_URI"]);


DB::Init();


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


api("POST", "user", function () {
    $controlerr = new UserController();
    $controlerr->createUser();
});
api("delete", "user/:id", function ($params) {
    $controller = new UserController();
    $controller->deleteUser($params);
});
api("GET", "user/:id", function ($params) {
    $controller = new UserController();
    $controller->getUser($params);
});
api("GET", "user", function () {
    $controller = new UserController();
    $controller->getAllUser();
});

api("PUT", "updateUser/:id", function ($params) {
    $controller = new UserController();
    $controller->updateUser($params);
});

api("POST", "login", function () {
    $controller = new UserController();
    $controller->login();
});
http_response_code(404);

die();
