<?php
declare(strict_types=1);

use core\Application;
use modules\office\OfficeController;
use shared\enums\ParamKeys;
use shared\enums\RequestMethod;
use shared\middlewares\AdminGuard;
use shared\middlewares\JwtVerify;

/** @var Application $app */

$app->router->addRoute(RequestMethod::GET, "/offices", [JwtVerify::class], [OfficeController::class, 'findAll']);
$app->router->addRoute(RequestMethod::GET, "/offices/:".ParamKeys::OFFICE_ID->value, null, [OfficeController::class, 'findOne']);
$app->router->addRoute(RequestMethod::POST, "/offices", [JwtVerify::class, AdminGuard::class], [OfficeController::class, 'create']);
$app->router->addRoute(RequestMethod::PATCH, "/offices/:".ParamKeys::OFFICE_ID->value, [JwtVerify::class, AdminGuard::class], [OfficeController::class, 'update']);
$app->router->addRoute(RequestMethod::DELETE, "/offices/:".ParamKeys::OFFICE_ID->value, [JwtVerify::class, AdminGuard::class], [OfficeController::class, 'delete']);


