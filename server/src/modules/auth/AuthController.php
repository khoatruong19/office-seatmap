<?php
declare( strict_types=1 );

namespace modules\auth;

use core\HttpStatus;
use core\Request;
use core\Response;
use core\Controller;
use core\SessionManager;
use modules\auth\dto\LoginUserDto;
use modules\auth\dto\RegisterUserDto;
use modules\user\UserService;
use shared\enums\AuthResponse;
use shared\enums\StoreKeys;
use shared\exceptions\ResponseException;

class AuthController extends Controller
{
    public function __construct(
        public Request               $request,
        public Response              $response,
        private readonly AuthService $authService,
        private readonly UserService $userService
    )
    {}

    /**
     * @return void
     * @throws ResponseException
     */
    public function register(): void
    {
        $this->requestBodyValidation(require_once "validation/register.php");
        $raw_data = $this->request->getBody();
        $register_user_dto = RegisterUserDto::fromArray($raw_data);
        $id = $this->authService->register($register_user_dto);
        $this->response->response(HttpStatus::$OK, AuthResponse::REGISTER_SUCCESS->value, null, $id);
    }

    /**
     * @return void
     * @throws ResponseException
     */
    public function login(): void
    {
        $this->requestBodyValidation(require_once "validation/login.php");
        $raw_data = $this->request->getBody();
        $login_user_dto = LoginUserDto::fromArray($raw_data);
        $user_credentials = $this->authService->login($login_user_dto);
        $this->response->response(HttpStatus::$OK, AuthResponse::LOGIN_SUCCESS->value, null, $user_credentials);
    }

    /**
     * @return void
     * @throws ResponseException
     */
    public function getCurrentUser(): void
    {
        $user_id = $this->request->getValue(StoreKeys::USER_ID->value);
        $user = $this->userService->getCurrentUser(strval($user_id));

        $this->response->response(HttpStatus::$OK, AuthResponse::ME_SUCCESS->value, null, $user);
    }

    /**
     * @return void
     * @throws ResponseException
     */
    public function logout(): void
    {
        $this->request->deleteValue(StoreKeys::USER_ID->value);
        $this->request->deleteValue(StoreKeys::USER_ROLE->value);
        $this->response->response(HttpStatus::$OK, AuthResponse::LOGOUT_SUCCESS->value, null, true);
    }
}