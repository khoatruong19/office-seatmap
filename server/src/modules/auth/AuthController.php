<?php

namespace modules\auth;

use core\HttpStatus;
use core\Request;
use core\Response;
use shared\middlewares\ValidationIncomingData;
use modules\user\UserService;
use http\Client\Curl\User;
use shared\exceptions\ResponseException;

class AuthController
{
    public function __construct(
        protected Request            $request,
        protected Response           $response,
        private readonly UserService $userService)
    {
    }

    /**
     * @throws ResponseException
     */
    public function register()
    {
        $requestBody = $this->request->getBody();

        $validation = new ValidationIncomingData($requestBody,
            [
                "email" => FILTER_VALIDATE_EMAIL,
                "username" => [
                    'filter' => FILTER_VALIDATE_REGEXP,
                    'options' => ['regexp' => '/.+/'],
                ],
                "password" => [
                    'filter' => FILTER_VALIDATE_REGEXP,
                    'options' => ['regexp' => '/.+/'],
                ],
            ], [
                "email" => "Email is invalid!",
                "username" => "Username cannot be null",
                "password" => "Password cannot be null",
            ]);
        $validation->execute();

        $this->userService->register($requestBody);

        return $this->response->response(HttpStatus::$OK, "Register successfully!");
    }

    /**
     * @throws ResponseException
     */
    public function login()
    {
        $requestBody =  $this->request->getBody();

        $validation = new ValidationIncomingData($requestBody,
            [
                "username" => [
                    'filter' => FILTER_VALIDATE_REGEXP,
                    'options' => ['regexp' => '/.+/'],
                ],
                "password" => [
                    'filter' => FILTER_VALIDATE_REGEXP,
                    'options' => ['regexp' => '/.+/'],
                ],
            ], [
                "username" => "Username cannot be null",
                "password" => "Password cannot be null",
            ]);
        $validation->execute();

        $token = $this->userService->login($requestBody);

        return $this->response->response(HttpStatus::$OK, $token);
    }

        /**
     * @throws ResponseException
     */
    public function hello()
    {
        return $this->request->validateBody([
            'email' => 'required',
        ]);
    }
}