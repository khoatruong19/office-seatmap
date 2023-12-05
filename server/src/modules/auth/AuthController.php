<?php
declare( strict_types=1 );

namespace modules\auth;

use core\HttpStatus;
use core\Request;
use core\Response;
use core\Controller;
use core\SessionManager;
use modules\user\UserService;
use shared\exceptions\ResponseException;

class AuthController extends Controller
{
    public function __construct(
        public Request               $request,
        public Response              $response,
        private readonly AuthService $authService,
        private readonly UserService $userService)
    {

    }

    /**
     * @return void
     * @throws ResponseException
     */
    public function register(): void
    {
        $this->requestBodyValidation([
            'email' => 'required|min:8|pattern:email',
            'full_name' => 'required|min:8',
            'password' => 'required|min:8'
        ]);
        $request_body = $this->request->getBody();
        $id = $this->authService->register($request_body);

        $this->response->response(HttpStatus::$OK, "Register successfully!", $id, null);
    }

    /**
     * @throws ResponseException
     * @response => {status: OK, messages: string, data: {$id: string}}
     */
    public function login(): void
    {
        $this->requestBodyValidation([
            'email' => 'required|min:8|pattern:email',
            'password' => 'required'
        ]);
        $body = $this->request->getBody();
        $user_credentials = $this->authService->login($body);

        $this->response->response(HttpStatus::$OK, "Login successfully!", $user_credentials);
    }

    /**
     * @return void
     * @throws ResponseException
     */
    public function me(): void
    {
        $user_id = SessionManager::get("userId");
        $user = $this->userService->me($user_id);

        $this->response->response(HttpStatus::$OK, "Welcome back!", $user, null);
    }

    /**
     * @return void
     * @throws ResponseException
     */
    public function logout(): void
    {
        $is_logout = $this->authService->logout();
        if(!$is_logout) throw new ResponseException(HttpStatus::$UNAUTHORIZED,"Not authorized!");

        $this->response->response(HttpStatus::$OK, "Logout successfully!", true, null);
    }
}