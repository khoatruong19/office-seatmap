<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization');
header('Access-Control-Allow-Methods: POST, GET, PUT, PATCH, DELETE, OPTIONS');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
 header('HTTP/1.1 200 OK');
 exit();
}

require_once __DIR__ . '/../vendor/autoload.php';

use core\Application;

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$container = require __DIR__.'/../app/bootstrap.php';
$app = $container->get(Application::class);

require_once __DIR__ . "/../src/modules/auth/routes.php";
require_once __DIR__ . "/../src/modules/user/routes.php";
require_once __DIR__ . "/../src/modules/office/routes.php";
require_once __DIR__ . "/../src/modules/seat/routes.php";

$app->run();