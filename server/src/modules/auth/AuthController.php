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
        private readonly AuthService $authService)
    {
    }

    /**
     * @throws ResponseException
     */
    public function register()
    {
        $errors = $this->request->validateBody([
            'email' => 'required|min:8|pattern:email',
            'full_name' => 'required|min:8',
            'password' => 'required'
        ]);

        $this->checkBodyValidationError($errors);

        $requestBody = $this->request->getBody();

        $id = $this->authService->register($requestBody);

        return $this->response->response(HttpStatus::$OK, "Register successfully!", $id);
    }

    /**
     * @throws ResponseException
     */
    public function login()
    {
        $errors = $this->request->validateBody([
            'email' => 'required|min:8|pattern:email',
            'password' => 'required'
        ]);

        $this->checkBodyValidationError($errors);

        $body = $this->request->getBody();
        // $this->userService->create($body);
        return "OJ";
    }

    public function hello()
    {
        $errors = $this->request->validateBody([
            'email' => 'required|pattern:email|min:8',
        ]);

        $this->checkBodyValidationError($errors);
    }
}