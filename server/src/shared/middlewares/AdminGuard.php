<?php

namespace shared\middlewares;

use core\HttpStatus;
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
     * @throws ResponseException
     */
    public function execute(): bool
    {
        $user_id = $_SESSION["userId"];

        $role = $this->user_service->getUserRole($user_id);
        
        if($role != UserRole::ADMIN->value) throw new ResponseException(HttpStatus::$UNAUTHORIZED, "Not authorizied!");

        return true;
    }
}