<?php

namespace shared\middlewares;

use core\HttpStatus;
use modules\auth\JwtService;
use shared\enums\EnumTypeJwt;
use shared\exceptions\ResponseException;
use shared\interfaces\IMiddleware;

class AuthorizeRequest implements IMiddleware
{

    public function __construct(private readonly JwtService $jwtService)
    {

    }

    /**
     * @throws ResponseException
     */
    public function execute(): bool
    {
        if(!array_key_exists("Authorization", getallheaders())) {
            throw new ResponseException(HttpStatus::$UNAUTHORIZED, "No token");
        }

        $token = getallheaders()["Authorization"];
        $token = str_replace("Bearer ", "", $token);
        $payload = $this->jwtService->verifyToken(EnumTypeJwt::ACCESS_TOKEN, $token);
        $userId = $payload->userId;
        $_SESSION["userId"] = $userId;

        return true;
    }
}