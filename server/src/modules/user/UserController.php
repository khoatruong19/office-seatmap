<?php
declare( strict_types=1 );
namespace modules\user;

use core\Controller;
use core\HttpStatus;
use core\Request;
use core\Response;
use modules\user\dto\CreateUserDto;
use modules\user\dto\UpdateProfileDto;
use modules\user\dto\UpdateUserDto;
use shared\enums\ParamKeys;
use shared\enums\UserResponse;
use shared\exceptions\ResponseException;

class UserController extends Controller
{
    public function __construct(
        public Request               $request,
        public Response              $response,
        private readonly UserService $userService)
    {
    }

    /**
     * @return null
     * @throws ResponseException
     */
    public function findAll()
    {
        $users = $this->userService->findAll();
        $this->response->response(HttpStatus::$OK, UserResponse::GET_ALL_SUCCESS->value, null, $users);
    }

    /**
     * @return null
     * @throws ResponseException
     */
    public function create()
    {
        $this->requestBodyValidation([
            'email' => 'required|min:8|max:100|pattern:email',
            'full_name' => 'required|min:8|max:100',
            'password' => 'required|min:8|max:100',
            'role' => 'required'
        ]);
        $raw_data = $this->request->getBody();
        $create_user_dto = CreateUserDto::fromArray($raw_data);
        $id = $this->userService->create($create_user_dto);
        $this->response->response(HttpStatus::$OK, UserResponse::CREATE_USER_SUCCESS->value, null, $id);
    }

    /**
     * @return null
     * @throws ResponseException
     */
    public function update()
    {
        $this->requestBodyValidation([
            'email' => 'required|min:8|max:100|pattern:email',
            'full_name' => 'required|min:8|max:100',
            'role' => 'required'
        ]);
        $user_id = $this->request->getParam(ParamKeys::USER_ID->value);
        $raw_data = $this->request->getBody();
        $update_user_dto = UpdateUserDto::fromArray($raw_data);
        $data = $this->userService->updateOne($user_id, $update_user_dto);
        $this->response->response(HttpStatus::$OK, UserResponse::UPDATE_USER_SUCCESS->value, null, $data);
    }

    /**
     * @return null
     * @throws ResponseException
     */
    public function updateProfile()
    {
        $this->requestBodyValidation([
            'full_name' => 'min:8|max:100',
        ]);
        $user_id = $this->request->getParam(ParamKeys::USER_ID->value);
        $raw_data = $this->request->getBody();
        $update_profile_dto = UpdateProfileDto::fromArray($raw_data);
        $data = $this->userService->updateProfile($user_id, $update_profile_dto);
        $this->response->response(HttpStatus::$OK, UserResponse::UPDATE_PROFILE_SUCCESS->value, null, $data);
    }

    /**
     * @return null
     * @throws ResponseException
     */
    public function delete()
    {
        $user_id = $this->request->getParam(ParamKeys::USER_ID->value);
        $this->userService->delete($user_id);
        $this->response->response(HttpStatus::$OK, UserResponse::DELETE_USER_SUCCESS->value);
    }

    /**
     * @return null
     * @throws ResponseException
     */
    public function uploadAvatar()
    {
        if (!isset($_FILES["file"]))
        {
            throw new ResponseException(HttpStatus::$BAD_REQUEST, UserResponse::NO_FILE_FOUND->value);
        }

        $user_id = $this->request->getParam(ParamKeys::USER_ID->value);
        $data = $this->userService->uploadAvatar($user_id);
        $this->response->response(HttpStatus::$OK, UserResponse::UPLOAD_SUCCESS->value, null, $data);
    }
}