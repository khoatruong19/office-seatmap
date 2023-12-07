<?php
declare(strict_types=1);

use core\Application;
use modules\user\UserController;
use shared\enums\ParamKeys;
use shared\enums\RequestMethod;
use shared\middlewares\AdminGuard;
use shared\middlewares\JwtVerify;

/** @var Application $app */

$app->router->addRoute(RequestMethod::POST, "/users", [JwtVerify::class, AdminGuard::class], [UserController::class, 'create']);
$app->router->addRoute(RequestMethod::GET, "/users", [JwtVerify::class, AdminGuard::class], [UserController::class, 'findAll']);
$app->router->addRoute(RequestMethod::PATCH, "/users/profile/:".ParamKeys::USER_ID->value, [JwtVerify::class], [UserController::class, 'updateProfile']);
$app->router->addRoute(RequestMethod::POST, "/users/:".ParamKeys::USER_ID->value, [JwtVerify::class, AdminGuard::class], [UserController::class, 'update']);
$app->router->addRoute(RequestMethod::DELETE, "/users/:".ParamKeys::USER_ID->value, [JwtVerify::class, AdminGuard::class], [UserController::class, 'delete']);
$app->router->addRoute(RequestMethod::POST, "/users/:".ParamKeys::USER_ID->value."/upload-avatar", [JwtVerify::class], [UserController::class, 'uploadAvatar']);

