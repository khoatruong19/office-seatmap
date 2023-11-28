<?php

namespace modules\auth;


use core\HttpStatus;
use modules\user\UserService;
use shared\enums\EnumTypeJwt;
use shared\exceptions\ResponseException;
use modules\auth\JwtService;

class AuthService
{
    public function __construct(private readonly UserService $userService, private readonly JwtService $jwtService)
    {
    }

    public function register($registerData)
    {

        $existedEmail = $this->userService->getByEmail("email", $registerData['email']);

        if($existedEmail) {
            throw new ResponseException(HttpStatus::$BAD_REQUEST, "Email existed!");
        }

        $registerData["password"] = password_hash($registerData["password"], PASSWORD_BCRYPT);
        $id = $this->userService->create($registerData);
        
        return array(
            "id" => $id,
        );
    }

    public function login($loginDto)
    {
        $matchedUser = $this->userRepository->findOne("username", $loginDto["username"]);

        if (!$matchedUser) {
            throw new ResponseException(HttpStatus::$BAD_REQUEST, "Username or password wrong!");
        }

        $isMatchedPassword = password_verify($loginDto["password"], $matchedUser["password"]);

        if (!$isMatchedPassword) {
            throw new ResponseException(HttpStatus::$BAD_REQUEST, "Username or password wrong!");
        }

        $accessToken = $this->jwtService->generateToken($matchedUser["id"], EnumTypeJwt::ACCESS_TOKEN);
        $refreshToken = $this->jwtService->generateToken($matchedUser["id"], EnumTypeJwt::REFRESH_TOKEN);

        return array(
            "accessToken" => $accessToken,
            "refreshToken" => $refreshToken,
        );
    }
}