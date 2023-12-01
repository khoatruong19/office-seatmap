<?php
declare(strict_types=1);

namespace modules\user;

use core\HttpStatus;
use modules\user\UserRepository;
use shared\enums\EnumTypeJwt;
use shared\exceptions\ResponseException;
use shared\enums\UserRole;
use modules\auth\JwtService;
use Cloudinary\Api\Upload\UploadApi;

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
        return $this->user_repository->create($data);
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
    public function me(int $user_id)
    {
        $user = $this->user_repository->findOne("id", strval($user_id));

        return array(
            "user" => $user,
        );
    }

    /**
     * @throws ResponseException
     */
    public function updateOne(string $user_id, array $data)
    {
        $role = $_SESSION['role'];
        if(isset($data['password']) && $role == UserRole::ADMIN->value){
            $data['password'] = password_hash($data["password"], PASSWORD_BCRYPT);
        }
        else unset($data['password']);

       $is_user_updated = $this->user_repository->updateOne($user_id, $data);
       if(!$is_user_updated) throw new ResponseException(HttpStatus::$INTERNAL_SERVER_ERROR,"Update user fail!");

       $user = $this->user_repository->findOne("id", $user_id);

       return $user;
    }

    /**
     * @throws ResponseException
     */
    public function delete(string $user_id)
    {
       $is_deleted = $this->user_repository->delete($user_id);

       if(!$is_deleted) throw new ResponseException(HttpStatus::$INTERNAL_SERVER_ERROR,"Delete user fail!");
    }

     /**
     * @throws ResponseException
     */
    public function upload(string $user_id)
    {
        $max_file_size = 800000;

        $allow_types = array('jpg', 'png', 'jpeg', 'gif');

        $file_name_array = explode(".", $_FILES["file"]["name"]);

        $ext = $file_name_array[count($file_name_array) - 1];

        if ($_FILES["file"]["size"] > $max_file_size) throw new ResponseException(HttpStatus::$BAD_REQUEST,"File too large!"); 

        if (!in_array($ext, $allow_types)) throw new ResponseException(HttpStatus::$BAD_REQUEST,"File wrong format!");

        try {
            $options = [
                'public_id' => 'profile_user_'.$user_id, // Set the name of the file here
            ];
            
            $data = (new UploadApi())->upload($_FILES["file"]['tmp_name'], $options);
            $this->user_repository->updateOne($user_id, ['avatar' => $data['url']]);
            return $data['url'];

        } catch (Exception $e) {
            throw new ResponseException(HttpStatus::$INTERNAL_SERVER_ERROR,"Can't upload file!");
        }
    }
}