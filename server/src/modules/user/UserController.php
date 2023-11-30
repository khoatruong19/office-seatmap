<?php

namespace modules\user;

use core\HttpStatus;
use core\Request;
use core\Response;
use modules\user\UserService;
use shared\exceptions\ResponseException;

class UserController
{
    public function __construct(
        public Request            $request,
        public Response           $response,
        private readonly UserService $user_service)
    {
    }

    /**
     * @throws ResponseException
     */
    public function uploadProfile()
    {
        if (!isset($_FILES["file"]))
        {
            throw new ResponseException(HttpStatus::$BAD_REQUEST,"No file found!");
        }
        
        $user_id = $this->request->getParam("userId");

        if (!$user_id)
        {
            throw new ResponseException(HttpStatus::$UNAUTHORIZED,"Not authorized!");
        }

        $data = $this->user_service->uploadProfile($user_id);
        return $this->response->response(HttpStatus::$OK, "Upload successfully!", $data);
    }
}