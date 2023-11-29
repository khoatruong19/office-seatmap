<?php
declare(strict_types=1);

namespace modules\user;


use core\HttpStatus;
use modules\user\UserRepository;
use shared\enums\EnumTypeJwt;
use shared\exceptions\ResponseException;
use modules\auth\JwtService;

class UserService
{
    public function __construct(private readonly UserRepository $user_repository, private readonly JwtService $jwt_service)
    {
    }

    /**
     * @throws ResponseException
     */
    public function create(array $data)
    {
        return $this->user_repository->save($data);
    }

    /**
     * @throws ResponseException
     */
    public function findOne(string $field, string $value)
    {
        return $this->user_repository->findOne($field, $value);
    }

    /**
     * @throws ResponseException
     */
    public function getUserRole(string $user_id)
    {
        return $this->user_repository->getRole($user_id);
    }

    /**
     * @throws ResponseException
     */
    public function me(int $userId)
    {
        $matched_user = $this->user_repository->findOne("id", $userId);

        return array(
            "username" => $matched_user["username"],
            "email" => $matched_user["email"],
        );
    }

    /**
     * 
     */
}