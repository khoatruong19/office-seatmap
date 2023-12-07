<?php
    declare( strict_types=1 );

    namespace modules\cloudinary;

    use Cloudinary\Api\ApiResponse;
    use core\HttpStatus;
    use Exception;
    use shared\enums\CloudinaryResponse;
    use shared\exceptions\ResponseException;
    use Cloudinary\Api\Upload\UploadApi;

    class CloudinaryService
    {
        public function __construct()
        {
        }

        /**
         * @param mixed $file
         * @param array $options
         * @return ApiResponse
         * @throws ResponseException
         */
        public function uploadFile(mixed $file, array $options = []): ApiResponse
        {
            $max_file_size = 800000;
            $allow_types = array('jpg', 'png', 'jpeg', 'gif');
            $file_name_array = explode(".", $file["name"]);
            $ext = $file_name_array[count($file_name_array) - 1];

            if ($file["size"] > $max_file_size) throw new ResponseException(HttpStatus::$BAD_REQUEST, CloudinaryResponse::FILE_TOO_LARGE->value);

            if (!in_array($ext, $allow_types)) throw new ResponseException(HttpStatus::$BAD_REQUEST, CloudinaryResponse::FILE_WRONG_FORMAT->value);

            try {
                return (new UploadApi())->upload($_FILES["file"]['tmp_name'], $options);
            } catch (Exception $e) {
                throw new ResponseException(HttpStatus::$INTERNAL_SERVER_ERROR, CloudinaryResponse::UPLOAD_FILE_FAIL->value);
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
                (new UploadApi())->destroy($public_id, $options);
            } catch (Exception $e) {
                throw new ResponseException(HttpStatus::$INTERNAL_SERVER_ERROR, CloudinaryResponse::DELETE_FILE_FAIL->value);
            }
        }
    
    }