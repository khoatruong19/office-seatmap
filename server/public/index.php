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

// $email = 'example@email.com';
// $username = 'admin';
// $password = 'test';
// $age = 29;

// $val = new Validation();
// $val->name('email')->value($email)->pattern('email')->required();
// $val->name('username')->value($username)->pattern('alpha')->required();
// $val->name('password')->value($password)->customPattern('[A-Za-z0-9-.;_!#@]{5,15}')->required();

// if($val->isSuccess()){
//     echo "Validation ok!";
// }else{
//     echo "Validation error!";
//     var_dump($val->getErrors());
// }

$app->router->addRoute(RequestMethod::GET, "/", null, [AuthController::class, 'hello']);
// $app->router->addRoute(RequestMethod::POST, "/auth/login", null, [AuthController::class, 'login']);
// $app->router->addRoute(RequestMethod::POST, "/auth/register", null, [AuthController::class, 'register']);
// $app->router->addRoute(RequestMethod::GET, "/users/me", AuthorizeRequest::class, [UserController::class, 'getMe']);

$app->run();