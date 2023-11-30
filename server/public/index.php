<?php
 header('Access-Control-Allow-Origin: *');
 header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization');
 header('Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS');
 header('Content-Type: application/json');
 
 if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
     header('HTTP/1.1 200 OK');
     exit();
 }

require_once __DIR__ . '/../vendor/autoload.php';

use core\Application;
use core\Validation;
use modules\user\UserController;
use modules\auth\AuthController;
use shared\enums\RequestMethod;
use shared\middlewares\JwtVerify;
use shared\middlewares\AdminGuard;

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

session_start();

$container = require __DIR__.'/../app/bootstrap.php';
$app = $container->get(Application::class);

$app->router->addRoute(RequestMethod::GET, "/", [JwtVerify::class, AdminGuard::class], [AuthController::class, 'hello']);
$app->router->addRoute(RequestMethod::POST, "/auth/login", null, [AuthController::class, 'login']);
$app->router->addRoute(RequestMethod::POST, "/auth/register", null, [AuthController::class, 'register']);
$app->router->addRoute(RequestMethod::GET, "/auth/me", [JwtVerify::class], [AuthController::class, 'me']);
$app->router->addRoute(RequestMethod::GET, "/auth/logout", [JwtVerify::class], [AuthController::class, 'logout']);

$app->router->addRoute(RequestMethod::POST, "/users/{userId}/upload", null, [UserController::class, 'uploadProfile']);

$app->run();