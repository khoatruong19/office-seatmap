<?php

declare(strict_types=1);

namespace modules\user;

use core\HttpStatus;
use modules\cloudinary\CloudinaryService;
use modules\user\dto\CreateUserDto;
use modules\user\dto\UpdateProfileDto;
use modules\user\dto\UpdateUserDto;
use shared\enums\UserResponse;
use shared\exceptions\ResponseException;

class UserService
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly CloudinaryService $cloudinaryService
    ) {
    }

    /**
     * @param mixed $user
     * @return void
     */
    private function removeUserPasswordReturn(mixed &$user): void
    {
        if(!isset($user['password'])) return;
        unset($user['password']);
    }

    /**
     * @param string $user_id
     * @return mixed
     * @throws ResponseException
     */
    public function checkUserExists(string $user_id): mixed
    {
        $user = $this->userRepository->findOne("id", $user_id);
        if (!$user) {
            throw new ResponseException(HttpStatus::$BAD_REQUEST, UserResponse::NOT_FOUND->value);
        }

        return $user;
    }

    /**
     * @param string $email
     * @return void
     * @throws ResponseException
     */
    public function checkEmailExists(string $email): void
    {
        $existing_email = $this->userRepository->findOne("email", $email);
        if ($existing_email) {
            throw new ResponseException(HttpStatus::$BAD_REQUEST, UserResponse::EMAIL_EXISTS->value);
        }
    }

    /**
     * @param string $public_id
     * @param UserEntity $user_entity
     * @return void
     * @throws ResponseException
     */
    public function uploadAndSetAvatarUrl(string $public_id, UserEntity &$user_entity): void
    {
        $upload_avatar_url = $this->uploadAndGetFileUrl($public_id);
        if ($upload_avatar_url) {
            $user_entity->setAvatar($upload_avatar_url);
        }
    }

    /**
     * @param CreateUserDto $create_user_dto
     * @return array
     * @throws ResponseException
     */
    public function create(CreateUserDto $create_user_dto): array
    {
        $this->checkEmailExists($create_user_dto->getEmail());
        $hashed_password = password_hash($create_user_dto->getPassword(), PASSWORD_BCRYPT);
        $user_entity = new UserEntity(
            $create_user_dto->getEmail(),
            $create_user_dto->getFullName(),
            $hashed_password,
            $create_user_dto->getRole()
        );
        $this->uploadAndSetAvatarUrl('profile_user_' . $user_entity->getEmail(), $user_entity);
        $id = $this->userRepository->create($user_entity->toArray());
        return [
            "id" => $id
        ];
    }

    /**
     * @param string $field
     * @param string $value
     * @return mixed|null
     * @throws ResponseException
     */
    public function findOne(string $field, string $value): mixed
    {
        return $this->userRepository->findOne($field, $value);
    }

    /**
     * @return array|bool
     */
    public function findAll(): bool|array
    {
        return $this->userRepository->findAll();
    }

    /**
     * @param string $user_id
     * @return array
     * @throws ResponseException
     */
    public function getCurrentUser(string $user_id): array
    {
        $user = $this->checkUserExists($user_id);
        $this->removeUserPasswordReturn($user);
        return [
            "user" => $user
        ];
    }

    /**
     * @param string $user_id
     * @param UpdateUserDto $update_user_dto
     * @return array
     * @throws ResponseException
     */
    public function updateOne(string $user_id, UpdateUserDto $update_user_dto): array
    {
        $user = $this->checkUserExists($user_id);
        $user_entity = new UserEntity(
            $update_user_dto->getEmail(),
            $update_user_dto->getFullName(),
            $user['password'],
            $update_user_dto->getRole(),
            $user['avatar']
        );
        $this->uploadAndSetAvatarUrl('profile_user_' . $user_entity->getEmail(), $user_entity);
        $data = $user_entity->toArray();
        $is_user_updated = $this->userRepository->updateOne($user_id, $data);
        if (!$is_user_updated) {
            throw new ResponseException(HttpStatus::$INTERNAL_SERVER_ERROR, UserResponse::UPDATE_USER_FAIL->value);
        }

        $this->removeUserPasswordReturn($data);
        return $data;
    }

    /**
     * @param string $user_id
     * @param UpdateProfileDto $update_profile_dto
     * @return array
     * @throws ResponseException
     */
    public function updateProfile(string $user_id, UpdateProfileDto $update_profile_dto): array
    {
        $user = $this->checkUserExists($user_id);
        $user_entity = new UserEntity(
            $user['email'],
            $update_profile_dto->getFullName(),
            $user['password'],
            $user['role'],
            $user['avatar']
        );
        $data = $user_entity->toArray();
        $is_user_updated = $this->userRepository->updateOne($user_id, $user_entity->toArray());
        if (!$is_user_updated) {
            throw new ResponseException(HttpStatus::$INTERNAL_SERVER_ERROR, UserResponse::UPDATE_PROFILE_FAIL->value);
        }

        $this->removeUserPasswordReturn($data);
        return $data;
    }

    /**
     * @param string $user_id
     * @return void
     * @throws ResponseException
     */
    public function delete(string $user_id): void
    {
        $user = $this->checkUserExists($user_id);
        $is_deleted = $this->userRepository->delete($user_id);
        if (!$is_deleted) {
            throw new ResponseException(HttpStatus::$INTERNAL_SERVER_ERROR, UserResponse::DELETE_USER_FAIL->value);
        }

        if (isset($user['avatar'])) {
            $avatar_public_id = 'profile_user_' . $user['email'];
            $this->cloudinaryService->deleteFile($avatar_public_id);
        }
    }

    /**
     * @param string $user_id
     * @return false|mixed
     * @throws ResponseException
     */
    public function uploadAvatar(string $user_id): mixed
    {
        $user = $this->checkUserExists($user_id);
        $upload_avatar_url = $this->uploadAndGetFileUrl('profile_user_' . $user['email']);
        if (!$upload_avatar_url) {
            throw new ResponseException(HttpStatus::$BAD_REQUEST, UserResponse::NO_FILE_FOUND->value);
        }

        $this->userRepository->updateOne($user_id, ['avatar' => $upload_avatar_url]);
        return $upload_avatar_url;
    }

    /**
     * @param string $public_id
     * @return false|mixed|null
     * @throws ResponseException
     */
    public function uploadAndGetFileUrl(string $public_id): mixed
    {
        $file = $_FILES['file'] ?? null;
        if (!$file) {
            return null;
        }

        $options = [
            'public_id' => $public_id,
        ];
        $uploadedFile = $this->cloudinaryService->uploadFile($file, $options);
        return $uploadedFile['url'];
    }
}