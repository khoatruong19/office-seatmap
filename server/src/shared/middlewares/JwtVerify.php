<?php

declare(strict_types=1);

namespace shared\middlewares;

use core\HttpStatus;
use core\Request;
use modules\auth\JwtService;
use shared\enums\AuthResponse;
use shared\enums\EnumTypeJwt;
use shared\enums\StoreKeys;
use shared\exceptions\ResponseException;
use shared\interfaces\IMiddleware;

class JwtVerify implements IMiddleware
{

    public function __construct(public Request $request, private readonly JwtService $jwtService)
    {
    }

    /**
     * @return bool
     * @throws ResponseException
     */
    public function execute(): bool
    {
        if (!array_key_exists("authorization", getallheaders())) {
            throw new ResponseException(HttpStatus::$UNAUTHORIZED, AuthResponse::UNAUTHORIZED->value);
        }

        $token = getallheaders()["authorization"];
        $token = str_replace("Bearer ", "", $token);
        $payload = $this->jwtService->verifyToken(EnumTypeJwt::ACCESS_TOKEN, $token);
        if (!$payload) {
            throw new ResponseException(HttpStatus::$UNAUTHORIZED, AuthResponse::UNAUTHORIZED->value);
        }

        $user_id = $payload->userId;
        $role = $payload->role;
        $this->request->storeValue(StoreKeys::USER_ID->value, $user_id);
        $this->request->storeValue(StoreKeys::USER_ROLE->value, $role);
        return true;
    }
}