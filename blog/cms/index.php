<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once 'vendor/autoload.php';

const BASE_DIR = '/xon_onboarding/blog/cms';
const SRC_DIR = "/src";
$request_uri = $_SERVER['REQUEST_URI'];
if (substr($request_uri, 0, strlen(BASE_DIR)) === BASE_DIR) {
    $request_uri = substr($request_uri, strlen(BASE_DIR));
}

$routes = [
    '' => '/auth/login.view.php',
    '/' => '/auth/login.view.php',
    '/login' => '/auth/login.view.php',
    '/dashboard' => '/Dashboard/Dashboard.php',
    '/post' => '/post/Post.php',
    '/category' => '/category/Category.php',
    '/profile' => '/profile/Profile.php',
    '/settings' => '/Settings/Settings.php',
    '/comments' => '/Comments/Comments.php',
    '/post/new' => '/post/PostCreate.php',
    '/post/edit/(\d+)' => '/post/PostEdit.php'
];

$matched = false;
foreach ($routes as $route => $file) {
    if (preg_match("~^$route$~", $request_uri, $matches)) {
        if (strpos($route, ':') !== false) {
            $file = preg_replace("~:$~", $matches[1], $file);
        }
        require __DIR__ . SRC_DIR . $file;
        $matched = true;
        break;
    }
}

if (!$matched) {
    http_response_code(404);
    require __DIR__ . '/404.php';
}
