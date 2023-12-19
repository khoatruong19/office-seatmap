<?php

declare(strict_types=1);

use core\Application;
use modules\seat\SeatController;
use shared\enums\RequestMethod;
use shared\middlewares\AdminGuard;
use shared\middlewares\JwtVerify;

/** @var Application $app */

$app->router->addRoute(
    RequestMethod::PATCH,
    "/seats/:seatId/set-user",
    [JwtVerify::class, AdminGuard::class],
    [SeatController::class, 'setUser']
);
$app->router->addRoute(
    RequestMethod::PATCH,
    "/seats/:seatId/remove-user",
    [JwtVerify::class, AdminGuard::class],
    [SeatController::class, 'removeUser']
);
$app->router->addRoute(
    RequestMethod::PATCH,
    "/seats/swap-users",
    [JwtVerify::class, AdminGuard::class],
    [SeatController::class, 'swapUsers']
);

