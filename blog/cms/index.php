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

switch ($request_uri) {
    case '':
    case '/':
        require __DIR__  . SRC_DIR . '/auth/login.view.php';
        break;

    case '/login':
        require __DIR__ . SRC_DIR . '/auth/login.view.php';
        break;

    case '/dashboard':
        require __DIR__ . SRC_DIR . '/Dashboard/Dashboard.php';
        break;
    case '/post':
        require __DIR__ . SRC_DIR . '/post/Post.php';
        break;
    case '/category':
        require __DIR__ . SRC_DIR . '/category/Category.php';
        break;
    case '/profile':
        require __DIR__ . SRC_DIR . '/profile/Profile.php';
        break;
    case '/settings':
        require __DIR__ . SRC_DIR . '/Settings/Settings.php';
        break;
    case '/comments':
        require __DIR__ . SRC_DIR . '/Comments/Comments.php';
        break;
    case '/post-create':
        require __DIR__ . SRC_DIR . '/post/PostCreate.php';
        break;
    case '/post-edit':
        require __DIR__ . SRC_DIR . '/post/PostEdit.php';
        break;
    default:
        http_response_code(404);
        require __DIR__   . '/404.php';
}
