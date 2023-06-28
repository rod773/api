<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers:  Content-Type, X-Auth-Token, Authorization, Origin');
header('Access-Control-Allow-Methods: *');

// require_once "src/Database.php";
// require_once "src/ErrorHandler.php";
// require_once "src/TaskController.php";
// require_once "src/TaskGateway.php";

require "vendor/autoload.php";

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




if ($resource != "task") {
    ;
    http_response_code(404);
    exit;
}



$database = new Database(
    $_ENV['DB_HOST'],
    $_ENV['DB_NAME'],
    $_ENV['DB_USER'],
    $_ENV['DB_PASS']
);


$task_gateway = new TaskGateway($database);

$controller = new TaskController($task_gateway);

$controller->processRequest($method, $id);