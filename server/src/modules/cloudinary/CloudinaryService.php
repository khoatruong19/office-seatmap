<?php

declare(strict_types=1);

namespace modules\cloudinary;

use core\HttpStatus;
use Exception;
use shared\enums\CloudinaryResponse;
use shared\exceptions\ResponseException;
use Cloudinary\Api\Upload\UploadApi;

class CloudinaryService
{
    private $uploadApi;

    public function __construct(UploadApi $uploadApi)
    {
        $this->uploadApi = $uploadApi;
    }

    const MAX_FILE_SIZE = 800000;
    const ALLOW_TYPES = ['jpg', 'png', 'jpeg', 'gif'];

    /**
     * @param mixed $file
     * @param array $options
     * @return mixed
     * @throws ResponseException
     */
    public function uploadFile(mixed $file, array $options = []): mixed
    {
        $file_name_array = explode(".", $file["name"]);
        $ext = $file_name_array[count($file_name_array) - 1];

        if ($file["size"] > self::MAX_FILE_SIZE) {
            throw new ResponseException(HttpStatus::$BAD_REQUEST, CloudinaryResponse::FILE_TOO_LARGE->value);
        }

        if (!in_array($ext, self::ALLOW_TYPES)) {
            throw new ResponseException(HttpStatus::$BAD_REQUEST, CloudinaryResponse::FILE_WRONG_FORMAT->value);
        }

        try {
            return $this->uploadApi->upload($_FILES["file"]['tmp_name'], $options);
        } catch (Exception $e) {
            throw new ResponseException(
                HttpStatus::$INTERNAL_SERVER_ERROR, CloudinaryResponse::UPLOAD_FILE_FAIL->value
            );
        }
    }

    /**
     * @param string $public_id
     * @param array $options
     * @return void
     * @throws ResponseException
     */
    public function deleteFile(string $public_id, array $options = []): void
    {
        try {
            $this->uploadApi->destroy($public_id, $options);
        } catch (Exception $e) {
            throw new ResponseException(
                HttpStatus::$INTERNAL_SERVER_ERROR, CloudinaryResponse::DELETE_FILE_FAIL->value
            );
        }
    }

}