<?php

namespace shared\middlewares;

use core\HttpStatus;
use modules\auth\JwtService;
use shared\enums\EnumTypeJwt;
use shared\exceptions\ResponseException;
use shared\interfaces\IMiddleware;

class JwtVerify implements IMiddleware
{

    public function __construct(private readonly JwtService $jwt_service)
    {
    }

    /**
     * @throws ResponseException
     */
    public function execute(): bool
    {
        if(!array_key_exists("authorization", getallheaders())) {
            throw new ResponseException(HttpStatus::$UNAUTHORIZED, "No token");
        }

        $token = getallheaders()["authorization"];
        $token = str_replace("Bearer ", "", $token);
        $payload = $this->jwt_service->verifyToken(EnumTypeJwt::ACCESS_TOKEN, $token);

        if(!$payload) throw new ResponseException(HttpStatus::$UNAUTHORIZED, "Token is invalid");

        $user_id = $payload->userId;
        $_SESSION["userId"] = $user_id;

        return true;
    }
}