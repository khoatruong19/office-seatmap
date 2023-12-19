<?php

declare(strict_types=1);

namespace shared\middlewares;

use core\HttpStatus;
use core\Request;
use modules\user\UserService;
use shared\enums\AuthResponse;
use shared\enums\StoreKeys;
use shared\enums\UserRole;
use shared\exceptions\ResponseException;
use shared\interfaces\IMiddleware;

class AdminGuard implements IMiddleware
{

    public function __construct(public Request $request, private readonly UserService $userService)
    {
    }

    /**
     * @return bool
     * @throws ResponseException
     */
    public function execute(): bool
    {
        $role = $this->request->getValue(StoreKeys::USER_ROLE->value);
        if ($role != UserRole::ADMIN->value) {
            throw new ResponseException(HttpStatus::$UNAUTHORIZED, AuthResponse::UNAUTHORIZED->value);
        }

        return true;
    }
}