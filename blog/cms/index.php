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
    case '/dashboard/style':
        require __DIR__ . '/assets/css/dashboard.css';
        break;
    default:
        http_response_code(404);
        require __DIR__   . '/404.php';
}
