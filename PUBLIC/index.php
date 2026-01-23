<?php
session_start();

header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET POST PUT PATCH DELETE");

require "../vendor/autoload.php";
require "../APP/CORE/init.php";

// middlware
require "../APP/MIDDLEWARE/AuthMiddleware.php";

$app = new App();
$app->handleController();








