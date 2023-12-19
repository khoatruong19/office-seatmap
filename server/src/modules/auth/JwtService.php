<?php
declare( strict_types=1 );

namespace modules\auth;

use core\HttpStatus;
use DateTime;
use Exception;
use \Firebase\JWT\JWT;
use Firebase\JWT\Key;
use shared\enums\AuthResponse;
use shared\enums\EnumTypeJwt;
use shared\exceptions\ResponseException;
use stdClass;

class JwtService
{
    public array $key;

    public function __construct()
    {
        $this->key = [
            EnumTypeJwt::ACCESS_TOKEN->name => $_ENV['JWT_ACCESS_KEY'],
            EnumTypeJwt::REFRESH_TOKEN->name => $_ENV['JWT_REFRESH_KEY'],
        ];
    }

    /**
     * @param int $user_id
     * @param string $role
     * @param EnumTypeJwt $type
     * @return string
     */
    public function generateToken(int $user_id, string $role, EnumTypeJwt $type): string
    {
        $iat = new DateTime();
        $exp = clone $iat;
        $exp->modify('+1 day');
        $payload = array(
            "userId" => $user_id,
            "role" => $role,
            "iat" => $iat->getTimestamp(),
            "exp" => $exp->getTimestamp(),
        );
        return JWT::encode($payload, $this->key[$type->name],'HS256');
    }

    /**
     * @param EnumTypeJwt $type
     * @param string $token
     * @return stdClass
     * @throws ResponseException
     */
    public function verifyToken(EnumTypeJwt $type, string $token): stdClass
    {
        try{
            return JWT::decode($token, new Key($this->key[$type->name], 'HS256'));
        }catch(Exception) {
            throw new ResponseException(HttpStatus::$UNAUTHORIZED, AuthResponse::UNAUTHORIZED->value);
        }
    }
}