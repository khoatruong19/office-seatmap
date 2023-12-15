<?php

namespace __test__;

use core\HttpStatus;
use modules\auth\AuthService;
use modules\auth\dto\LoginUserDto;
use modules\auth\dto\RegisterUserDto;
use modules\auth\JwtService;
use modules\user\UserService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use shared\enums\AuthResponse;
use shared\enums\UserResponse;
use shared\exceptions\ResponseException;

class AuthServiceTest extends TestCase
{
    private AuthService $authService;
    private MockObject $userServiceMock;
    private MockObject $jwtServiceMock;

    public function setUp(): void
    {
        parent::setUp();
        $this->userServiceMock = $this->createMock(UserService::class);
        $this->jwtServiceMock = $this->createMock(JwtService::class);
        $this->authService = new AuthService($this->userServiceMock, $this->jwtServiceMock);
    }

    public function testLoginWithValidCredential()
    {
        $user_data = [
            "id" => 34,
            "email" => "test@gmail.com",
            "password" => "\$2y\$10\$nZ72rPoi8f0Jrj.uPTfxMOVydjwJ9XjaFrJugFOTp85MN.3VFceOu",
            "full_name" => "jdshfjk",
            "role" => "dsfjksdjf",
            "avatar" => "sdjkfgsjdkf",
            "created_at" => "sdfjlsdf",
            "updated_at" => "ksdklfhds"
        ];
        $access_token = "dsfdsfds";
        $expected_result = [
            "user" => [
                "id" => 34,
                "email" => "test@gmail.com",
                "full_name" => "jdshfjk",
                "role" => "dsfjksdjf",
                "avatar" => "sdjkfgsjdkf",
                "created_at" => "sdfjlsdf",
                "updated_at" => "ksdklfhds"
            ],
            "accessToken" => $access_token
        ];
        $this->userServiceMock->expects($this->once())
            ->method("findOne")
            ->willReturn($user_data);
        $this->jwtServiceMock->expects($this->once())
            ->method("generateToken")
            ->willReturn($access_token);
        $login_user_dto = LoginUserDto::fromArray([
            "email" => "test@gmail.com",
            "password" => "123456tuna"
        ]);

        $result = $this->authService->login($login_user_dto);

        $this->assertEquals($expected_result, $result);
    }

    public function testLoginWithInvalidEmail()
    {
        $this->userServiceMock->expects($this->once())
            ->method("findOne")
            ->willReturn(null);
        $login_user_dto = LoginUserDto::fromArray([
            "email" => "test@gmail.com",
            "password" => "123456tuna"
        ]);

        $this->expectException(ResponseException::class);
        $this->expectExceptionMessage(AuthResponse::INVALID_CREDENTIAL->value);

        $this->authService->login($login_user_dto);
    }

    public function testLoginWithInvalidPassword()
    {
        $password = "123456tuna";
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $this->userServiceMock->expects($this->once())
            ->method("findOne")
            ->willReturn(["email" => "email", "password" => $hashed_password]);
        $login_user_dto = LoginUserDto::fromArray([
            "email" => "test@gmail.com",
            "password" => "1234565tuna"
        ]);

        $this->expectException(ResponseException::class);
        $this->expectExceptionMessage(AuthResponse::INVALID_CREDENTIAL->value);

        $this->authService->login($login_user_dto);
    }

    public function testUserDataWithNoPasswordReturnWhenLoginSuccess()
    {
        $password = "123456tuna";
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $this->userServiceMock->expects($this->once())
            ->method("findOne")
            ->willReturn(["id" => 1, "role" => "admin", "password" => $hashed_password]);
        $login_user_dto = LoginUserDto::fromArray([
            "email" => "test@gmail.com",
            "password" => "123456tuna"
        ]);

        $result = $this->authService->login($login_user_dto);

        $this->assertArrayNotHasKey("password", $result['user']);
    }

    public function testRegisterFailWithExistingEmail()
    {
        $this->userServiceMock->expects($this->once())
            ->method("create")
            ->willThrowException(new ResponseException(HttpStatus::$BAD_REQUEST, UserResponse::EMAIL_EXISTS->value));
        $register_user_dto = RegisterUserDto::fromArray([
            "email" => "test@gmail.com",
            "full_name" => "afdkf",
            "password" => "123456tuna",
        ]);

        $this->expectException(ResponseException::class);
        $this->expectExceptionMessage(UserResponse::EMAIL_EXISTS->value);

        $this->authService->register($register_user_dto);
    }

    public function testRegisterSuccessWithValidEmail()
    {
        $this->userServiceMock->expects($this->once())
            ->method("create")
            ->willReturn(["id" => 2]);
        $register_user_dto = RegisterUserDto::fromArray([
            "email" => "test@gmail.com",
            "full_name" => "afdkf",
            "password" => "123456tuna",
        ]);

        $result = $this->authService->register($register_user_dto);
        
        $this->assertEquals($result, ["id" => 2]);
    }
}
