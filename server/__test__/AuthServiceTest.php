<?php

namespace __test__;

use modules\auth\AuthService;
use modules\auth\dto\LoginUserDto;
use modules\auth\JwtService;
use modules\user\UserService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class AuthServiceTest extends TestCase
{
    private AuthService $authService;
    private MockObject $userServiceMock;
    public function setUp(): void
    {
        parent::setUp();
        $this->userServiceMock= $this->createMock(UserService::class);
        $this->jwtServiceMock= $this->createMock(JwtService::class);

        $this->authService = new AuthService($this->userServiceMock, $this->jwtServiceMock);
    }

    public function testLogin_success()
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
        $this->userServiceMock->expects($this->once())->method("findOne")->willReturn($user_data);
        $this->jwtServiceMock->expects($this->once())->method("generateToken")->willReturn($access_token);
        $login_user_dto = LoginUserDto::fromArray([
            "email" => "test@gmail.com",
            "password" => "123456tuna"
        ]);

        $result = $this->authService->login($login_user_dto);

        $this->assertEquals($expected_result, $result );
    }
}
