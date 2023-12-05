<?php
declare( strict_types=1 );
namespace modules\user;

use core\HttpStatus;
use core\Request;
use core\Response;
use core\Controller;
use shared\exceptions\ResponseException;

class UserController extends Controller
{
    public function __construct(
        public Request            $request,
        public Response           $response,
        private readonly UserService $user_service)
    {
    }

    /**
     * @return null
     * @throws ResponseException
     */
    public function findAll()
    {
        $data = $this->user_service->findAll();

        $this->response->response(HttpStatus::$OK, "Get all users successfuly!", $data, null);
    }

    /**
     * @return null
     * @throws ResponseException
     */
    public function create()
    {
        $this->requestBodyValidation([
            'email' => 'required|min:8|pattern:email',
            'full_name' => 'required|min:8',
            'password' => 'required|min:8',
            'role' => 'required'
        ]);

        $request_body = $this->request->getBody();

        $id = $this->user_service->create($request_body);

        $this->response->response(HttpStatus::$OK, "Create user successfully!", $id, null);
    }

    /**
     * @return null
     * @throws ResponseException
     */
    public function update()
    {
        $this->requestBodyValidation([
            'email' => 'required|min:8|pattern:email',
            'full_name' => 'required|min:8',
            'role' => 'required'
        ]);

        $user_id = $this->request->getParam("userId");

        if (!$user_id)
        {
            throw new ResponseException(HttpStatus::$BAD_REQUEST,"User id not found!");
        }

        $body = $this->request->getBody() ?? [];

        $data = $this->user_service->updateOne($user_id, $body);

        $this->response->response(HttpStatus::$OK, "Update successfully!", $data, null);
    }

    /**
     * @return null
     * @throws ResponseException
     */
    public function updateProfile()
    {
        $this->requestBodyValidation([
            'full_name' => 'min:8',
        ]);

        $user_id = $this->request->getParam("userId");

        if (!$user_id)
        {
            throw new ResponseException(HttpStatus::$BAD_REQUEST,"User id not found!");
        }

        $body = $this->request->getBody() ?? [];

        $data = $this->user_service->updateOne($user_id, $body);

        $this->response->response(HttpStatus::$OK, "Update successfully!", $data, null);
    }

    /**
     * @return null
     * @throws ResponseException
     */
    public function delete()
    {
        $user_id = $this->request->getParam("userId");

        if (!$user_id)
        {
            throw new ResponseException(HttpStatus::$BAD_REQUEST,"User id not found!");
        }

        $this->user_service->delete($user_id);

         $this->response->response(HttpStatus::$OK, "Delete successfully!", null, null);
    }

    /**
     * @return null
     * @throws ResponseException
     */
    public function upload()
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

        $data = $this->user_service->upload($user_id);
        $this->response->response(HttpStatus::$OK, "Upload successfully!", $data, null);
    }
}