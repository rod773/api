<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers:  Content-Type, X-Auth-Token, Authorization, Origin');
header('Access-Control-Allow-Methods: *');



require "vendor/autoload.php";

set_error_handler("ErrorHandler::handleError");
set_exception_handler("ErrorHandler::handleException");

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);

$dotenv->load();


$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$parts = array_filter(explode("/", $path));

//print_r($parts);

$resource = $parts[2] ?? null;

$id = $parts[3] ?? null;

$method = $_SERVER['REQUEST_METHOD'];

header("Content-type: application/json; charset=UTF-8");




if ($resource != "task") {;
    http_response_code(404);
    exit;
}


if (empty($_SERVER['HTTP_X_API_KEY'])) {

    http_response_code(400);

    json_encode([
        "message" => "Api Key is missing"
    ]);

    exit;
}

$api_key = $_SERVER['HTTP_X_API_KEY'];

echo $api_key;
exit;

$database = new Database(
    $_ENV['DB_HOST'],
    $_ENV['DB_NAME'],
    $_ENV['DB_USER'],
    $_ENV['DB_PASS']
);


$task_gateway = new TaskGateway($database);

$controller = new TaskController($task_gateway);

$controller->processRequest($method, $id);
