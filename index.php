<?php
date_default_timezone_set(timezoneId: "Europe/Lisbon");
$path = $_SERVER['DOCUMENT_ROOT'];
$path .= '/ProjectoAPISOSLink/config/config.php';

include_once $path;
require_once DATABASE_PATH;
require_once USERCONTROL_PATH;
require_once ERRORCONTROL_PATH;

header('Access-Control-Allow-Origin: *');

$parts = explode('/', $_SERVER['REQUEST_URI']);
array_shift($parts);

$method = $_SERVER['REQUEST_METHOD'];
$controller = $parts[1] ?? null;
$action = $parts[2] ?? null;
$extra = $parts[3] ?? null;


if($method == 'GET' && ($controller == null || $controller == '')) {
    header("Location: http://localhost/ProjectoAPISOSLink/main.php");
} elseif($method == 'POST' && $controller == 'users' && $extra == null) {
    $database_object = new DB();
    if(($action == 'signIn' || $action == 'signUp')) {
        $controller = new UserController($database_object);
        $controller->$action();
    } else {
        $controller = new ErrorController();
        $controller->error("Invalid API Endpoint.");
    }
} else {
    $controller = new ErrorController();
    $controller->error("Invalid API Request.");
}
?>