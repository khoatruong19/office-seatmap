<?php
require_once __DIR__ . '/../vendor/autoload.php';

use core\Application;
use core\Validation;
use modules\user\UserController;
use modules\auth\AuthController;
use shared\enums\RequestMethod;
use shared\middlewares\AuthorizeRequest;

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

session_start();

$container = require __DIR__.'/../app/bootstrap.php';
$app = $container->get(Application::class);

$app->router->addRoute(RequestMethod::GET, "/", null, [AuthController::class, 'hello']);
$app->router->addRoute(RequestMethod::POST, "/auth/login", null, [AuthController::class, 'login']);
$app->router->addRoute(RequestMethod::POST, "/auth/register", null, [AuthController::class, 'register']);
// $app->router->addRoute(RequestMethod::GET, "/users/me", AuthorizeRequest::class, [UserController::class, 'getMe']);

$app->run();