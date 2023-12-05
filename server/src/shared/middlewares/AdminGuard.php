<?php
declare( strict_types=1 );

namespace shared\middlewares;

use core\HttpStatus;
use core\SessionManager;
use modules\user\UserService;
use shared\enums\UserRole;
use shared\exceptions\ResponseException;
use shared\interfaces\IMiddleware;

class AdminGuard implements IMiddleware
{

    public function __construct(private readonly UserService $user_service)
    {

    }

    /**
     * @return bool
     * @throws ResponseException
     */
    public function execute(): bool
    {
        $role = SessionManager::get("role");

        if($role != UserRole::ADMIN->value) throw new ResponseException(HttpStatus::$UNAUTHORIZED, "Not authorizied!");

        return true;
    }
}