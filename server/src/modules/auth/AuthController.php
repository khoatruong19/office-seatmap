<?php

namespace modules\auth;

use core\HttpStatus;
use core\Request;
use core\Response;
use core\Controller;
use shared\middlewares\ValidationIncomingData;
use modules\user\UserService;
use modules\auth\AuthService;
use http\Client\Curl\User;
use shared\exceptions\ResponseException;

class AuthController extends Controller
{
    public function __construct(
        public Request $request,
        public Response $response,
        private readonly AuthService $auth_service)
    {
    }

    /**
     * @throws ResponseException
     */
    public function register()
    {
        $this->requestBodyValidation([
            'email' => 'required|min:8|pattern:email',
            'full_name' => 'required|min:8',
            'password' => 'required'
        ]);

        $request_body = $this->request->getBody();

        $id = $this->auth_service->register($request_body);

        return $this->response->response(HttpStatus::$OK, "Register successfully!", $id);
    }

    /**
     * @throws ResponseException
     */
    public function login()
    {
        $this->requestBodyValidation([
            'email' => 'required|min:8|pattern:email',
            'password' => 'required'
        ]);

        $body = $this->request->getBody();
        $user_credentials = $this->auth_service->login($body);

        return $this->response->response(HttpStatus::$OK, "Login successfully!", $user_credentials);

    }

    public function hello()
    {
        $this->requestBodyValidation([
            'email' => 'required|pattern:email|min:8',
        ]);
        return $this->response->response(HttpStatus::$OK, "Hello");
    }
}