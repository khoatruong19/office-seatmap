<?php

namespace modules\user;


use core\HttpStatus;
use modules\user\UserRepository;
use shared\enums\EnumTypeJwt;
use shared\exceptions\ResponseException;
use modules\auth\JwtService;

class UserService
{
    public function __construct(private readonly UserRepository $userRepository, private readonly JwtService $jwtService)
    {
    }

    /**
     * @throws ResponseException
     */
    public function register($registerDto)
    {
        $registerDto["password"] = password_hash($registerDto["password"], PASSWORD_BCRYPT);
        $this->userRepository->save($registerDto);
    }

    /**
     * @throws ResponseException
     */
    public function handleGetMe(int $userId)
    {
        $matchedUser = $this->userRepository->findOne("id", $userId);

        return array(
            "username" => $matchedUser["username"],
            "email" => $matchedUser["email"],
        );
    }

    /**
     * @throws ResponseException
     */
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