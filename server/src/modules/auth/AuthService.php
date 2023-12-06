<?php
declare( strict_types=1 );

namespace modules\auth;

use core\HttpStatus;
use core\SessionManager;
use modules\auth\dto\LoginUserDto;
use modules\auth\dto\RegisterUserDto;
use modules\user\dto\CreateUserDto;
use modules\user\UserEntity;
use modules\user\UserService;
use shared\enums\AuthResponse;
use shared\enums\EnumTypeJwt;
use shared\enums\SessionKeys;
use shared\enums\UserResponse;
use shared\exceptions\ResponseException;

class AuthService
{
    public function __construct(private readonly UserService $userService, private readonly JwtService $jwtService)
    {
    }

    /**
     * @param RegisterUserDto $register_user_dto
     * @return array
     * @throws ResponseException
     */
    public function register(RegisterUserDto $register_user_dto): array
    {
        $create_user_dto = CreateUserDto::fromArray($register_user_dto->toArray());
        return $this->userService->create($create_user_dto);
    }

    /**
     * @param LoginUserDto $login_user_dto
     * @return array
     * @throws ResponseException
     */
    public function login(LoginUserDto $login_user_dto): array
    {
        $existing_user = $this->userService->findOne("email", $login_user_dto->getEmail());
        if (!$existing_user) {
            throw new ResponseException(HttpStatus::$BAD_REQUEST,  AuthResponse::INVALID_CREDENTIAL->value);
        }

        $is_password_matched = password_verify($login_user_dto->getPassword(), $existing_user["password"]);
        if (!$is_password_matched) {
            throw new ResponseException(HttpStatus::$BAD_REQUEST, AuthResponse::INVALID_CREDENTIAL->value);
        }

        $access_token = $this->jwtService->generateToken($existing_user["id"], $existing_user["role"], EnumTypeJwt::ACCESS_TOKEN);
        unset($existing_user['password']);
        return array(
            "user" => $existing_user,
            "accessToken" => $access_token,
        );
    }

    /**
     * @return bool
     */
    public function logout(): bool
    {
        $user_id = SessionManager::get(SessionKeys::USER_ID->value);
        if(!$user_id) return false;

        SessionManager::set(SessionKeys::USER_ID->value, null);
        return true;
    }
}