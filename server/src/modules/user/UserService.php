<?php
declare(strict_types=1);

namespace modules\user;

use core\HttpStatus;
use modules\user\UserRepository;
use shared\enums\EnumTypeJwt;
use shared\exceptions\ResponseException;
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
    public function uploadProfile(int $user_id)
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
            return $this->user_repository->updateOne($user_id, ['avatar' => $data['url']]);

        } catch (Exception $e) {
            throw new ResponseException(HttpStatus::$INTERNAL_SERVER_ERROR,"Can't upload file!");
        }

        
    }
}