<?php
declare(strict_types=1);

use core\Application;
use modules\office\OfficeController;
use shared\enums\RequestMethod;
use shared\middlewares\AdminGuard;
use shared\middlewares\JwtVerify;

/** @var Application $app */

$app->router->addRoute(RequestMethod::POST, "/offices", null, [OfficeController::class, 'create']);


