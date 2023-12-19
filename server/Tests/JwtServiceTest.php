<?php


use core\HttpStatus;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use modules\auth\AuthService;
use modules\auth\dto\LoginUserDto;
use modules\auth\dto\RegisterUserDto;
use modules\auth\JwtService;
use modules\user\UserService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use shared\enums\AuthResponse;
use shared\enums\EnumTypeJwt;
use shared\enums\UserResponse;
use shared\exceptions\ResponseException;

ini_set('memory_limit', '256M');

class JwtServiceTest extends TestCase
{
    private JwtService $jwtService;

    public function setUp(): void
    {
        parent::setUp();
        $this->jwtService = new JwtService();
        $this->jwtService->key = [
            EnumTypeJwt::ACCESS_TOKEN->name => "jwtAccessToken",
            EnumTypeJwt::REFRESH_TOKEN->name => "jwtRefreshToken",
        ];
    }

    private function decodeToken(string $token, string $key): array
    {
        return (array) JWT::decode($token, new Key($key, 'HS256'));
    }

    public function testGenerateAccessTokenSuccess()
    {
        $user_id = 1;
        $role = "user";
        $type = EnumTypeJwt::ACCESS_TOKEN;

        $token = $this->jwtService->generateToken($user_id, $role, $type);

        $this->assertIsString($token);
        $decodedToken = $this->decodeToken($token, $this->jwtService->key[EnumTypeJwt::ACCESS_TOKEN->name]);

        $this->assertArrayHasKey('userId', $decodedToken);
        $this->assertEquals($user_id, $decodedToken['userId']);

        $this->assertArrayHasKey('role', $decodedToken);
        $this->assertEquals($role, $decodedToken['role']);

        $this->assertArrayHasKey('iat', $decodedToken);
        $this->assertArrayHasKey('exp', $decodedToken);
    }

    public function testVerifyTokenInvalidToken()
    {
        $type = EnumTypeJwt::ACCESS_TOKEN;
        $invalidToken = 'invalid_token';

        $this->expectException(ResponseException::class);
        $this->expectExceptionMessage(AuthResponse::UNAUTHORIZED->value);

        $this->jwtService->verifyToken($type, $invalidToken);
    }

    public function testVerifyTokenSuccess()
    {
        $user_id = 1;
        $role = "user";
        $type = EnumTypeJwt::ACCESS_TOKEN;

        $token = $this->jwtService->generateToken($user_id, $role, $type);
        $decodedToken = $this->jwtService->verifyToken($type, $token);

        $this->assertInstanceOf(stdClass::class, $decodedToken);
    }
}
