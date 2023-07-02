<?php

require "bootstrap.php";

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers:  Content-Type, X-Auth-Token, Authorization, Origin');
header('Access-Control-Allow-Methods: *');


$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$parts = array_filter(explode("/", $path));

//print_r($parts);

$resource = $parts[2] ?? null;

$id = $parts[3] ?? null;

$method = $_SERVER['REQUEST_METHOD'];



if ($resource != "task") {;
    http_response_code(404);
    exit;
}




$database = new Database(
    $_ENV['DB_HOST'],
    $_ENV['DB_NAME'],
    $_ENV['DB_USER'],
    $_ENV['DB_PASS']
);

$user_gateway = new UserGateway($database);

$auth = new Auth($user_gateway);

if (!$auth->autheticateAccessToken()) {
    exit;
};

echo "valid authetication";

exit;

$user_id = $auth->getUserId();



$task_gateway = new TaskGateway($database);

$controller = new TaskController($task_gateway, $user_id);

$controller->processRequest($method, $id);
