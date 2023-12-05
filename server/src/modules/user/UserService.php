<?php
declare(strict_types=1);

namespace modules\user;

use core\HttpStatus;
use shared\exceptions\ResponseException;
use shared\enums\UserRole;
use modules\auth\JwtService;
use modules\cloudinary\CloudinaryService;

class UserService
{
    public function __construct(private readonly UserRepository $user_repository, private readonly JwtService $jwt_service,
    private readonly CloudinaryService $cloudinary_service)
    {
    }

    /**
     * @param array $create_data
     * @return array
     * @throws ResponseException
     */
    public function create(array $create_data): array
    {
        $existing_email = $this->findOne("email", $create_data['email']);

        if($existing_email) {
            throw new ResponseException(HttpStatus::$BAD_REQUEST, "Email existed!");
        }

        $create_data["password"] = password_hash($create_data["password"], PASSWORD_BCRYPT);

        $id = $this->user_repository->create($create_data);

        $file = $_FILES['file'] ?? null;

        if($file){
            $options = [
                'public_id' => 'profile_user_'.$id,
            ];

            $uploadedFile = $this->cloudinary_service->uploadFile($file, $options);

            $this->user_repository->updateOne($id, ['avatar' => $uploadedFile['url']]);
        }

        return array(
            "id" => $id,
        );
    }

    /**
     * @param string $field
     * @param string $value
     * @return mixed|null
     * @throws ResponseException
     */
    public function findOne(string $field, string $value): mixed
    {
        return $this->user_repository->findOne($field, $value);
    }

    /**
     * @return array|bool
     */
    public function findAll(): bool|array
    {
        return $this->user_repository->findAll();
    }

    /**
     * @param string $user_id
     * @return mixed|null
     */
    public function getUserRole(string $user_id): mixed
    {
        return $this->user_repository->getRole($user_id);
    }

    /**
     * @param int $user_id
     * @return array
     * @throws ResponseException
     */
    public function me(int $user_id): array
    {
        $user = $this->user_repository->findOne("id", strval($user_id));

        if(!$user) throw new ResponseException(HttpStatus::$UNAUTHORIZED, "Not authorized!");

        return array(
            "user" => $user,
        );
    }

    /**
     * @param string $user_id
     * @param array $data
     * @return mixed|null
     * @throws ResponseException
     */
    public function updateOne(string $user_id, array $data): mixed
    {
        $file = $_FILES['file'] ?? null;

        if($file){
            $options = [
                'public_id' => 'profile_user_'.$user_id,
            ];

            $uploadedFile = $this->cloudinary_service->uploadFile($file, $options);

            $data['avatar'] = $uploadedFile['url'];
        }

        $is_user_updated = $this->user_repository->updateOne($user_id, $data);

        if(!$is_user_updated) throw new ResponseException(HttpStatus::$INTERNAL_SERVER_ERROR,"Update user fail!");

        return $this->user_repository->findOne("id", $user_id);
    }

    /**
     * @param string $user_id
     * @return void
     * @throws ResponseException
     */
    public function delete(string $user_id): void
    {
        $user = $this->user_repository->findOne("id", $user_id);

        if(!$user) throw new ResponseException(HttpStatus::$BAD_REQUEST,"No user found!");

        if($user['role'] == UserRole::ADMIN->value) throw new ResponseException(HttpStatus::$BAD_REQUEST,"No permission!");

        $is_deleted = $this->user_repository->delete($user_id);

        if(!$is_deleted) throw new ResponseException(HttpStatus::$INTERNAL_SERVER_ERROR,"Delete user fail!");
    }

    /**
     * @param string $user_id
     * @return false|mixed
     * @throws ResponseException
     */
    public function upload(string $user_id): mixed
    {
        $options = [
            'public_id' => 'profile_user_'.$user_id,
        ];

        $data = $this->cloudinary_service->uploadFile($_FILES['file'], $options);
        $this->user_repository->updateOne($user_id, ['avatar' => $data['url']]);
        return $data['url'];
    }
}