<?php
declare(strict_types=1);

use core\Application;
use modules\auth\AuthController;
use shared\enums\RequestMethod;
use shared\middlewares\JwtVerify;

/** @var Application $app */

$app->router->addRoute(RequestMethod::POST, "/auth/login", null, [AuthController::class, 'login']);
$app->router->addRoute(RequestMethod::POST, "/auth/register", null, [AuthController::class, 'register']);
$app->router->addRoute(RequestMethod::GET, "/auth/me", [JwtVerify::class], [AuthController::class, 'me']);
$app->router->addRoute(RequestMethod::GET, "/auth/logout", [JwtVerify::class], [AuthController::class, 'logout']);

