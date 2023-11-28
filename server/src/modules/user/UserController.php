<?php

namespace modules\user;

use core\HttpStatus;
use core\Request;
use core\Response;
use modules\user\UserService;
use shared\exceptions\ResponseException;

class UserController
{
    public function __construct(
        protected Request            $request,
        protected Response           $response,
        private readonly UserService $userService)
    {
    }

    /**
     * @throws ResponseException
     */
    public function getMe()
    {
        $userId = $_SESSION["userId"];
        $me = $this->userService->handleGetMe($userId);
        return $this->response->response(HttpStatus::$OK, $me);
    }
}