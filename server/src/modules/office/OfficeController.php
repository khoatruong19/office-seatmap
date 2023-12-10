<?php
declare( strict_types=1 );

namespace modules\office;

use core\HttpStatus;
use core\Request;
use core\Response;
use core\Controller;
use modules\office\dto\CreateOfficeDto;
use modules\office\dto\UpdateOfficeDto;
use shared\enums\OfficeResponse;
use shared\enums\ParamKeys;
use shared\exceptions\ResponseException;

class OfficeController extends Controller
{
    public function __construct(
        public Request $request,
        public Response $response,
        private readonly OfficeService $officeService)
    {
    }

    /**
     * @return void
     * @throws ResponseException
     */
    public function create(): void
    {
        $this->requestBodyValidation([
            'name' => 'required|min:4|max:100',
        ]);
        $raw_data = $this->request->getBody();
        $create_office_dto = CreateOfficeDto::fromArray($raw_data);
        $id = $this->officeService->create($create_office_dto);
        $this->response->response(HttpStatus::$OK, OfficeResponse::CREATE_OFFICE_SUCCESS->value , null, $id);
    }

    /**
     * @return void
     * @throws ResponseException
     */
    public function update(): void
    {
        $this->requestBodyValidation([
            'name' => 'required|min:4|max:100',
            'seats' => '',
            'blocks' => '',
            'visible' => 'is_bool',
        ]);
        $office_id = $this->request->getIntParam(ParamKeys::OFFICE_ID->value);
        $raw_data = $this->request->getBody();
        $raw_data['id'] = $office_id;
        $update_office_dto = UpdateOfficeDto::fromArray($raw_data);
        $this->officeService->update($update_office_dto);
        $this->response->response(HttpStatus::$OK, OfficeResponse::UPDATE_OFFICE_SUCCESS->value , null, $office_id);
    }

    /**
     * @return void
     * @throws ResponseException
     */
    public function findOne(): void
    {
        $office_id = $this->request->getParam(ParamKeys::OFFICE_ID->value);
        $office = $this->officeService->findOne("id", $office_id);
        $this->response->response(HttpStatus::$OK, OfficeResponse::GET_ONE_OFFICE_SUCCESS->value, null, $office);
    }

    /**
     * @return void
     */
    public function findAll(): void
    {
        $offices = $this->officeService->findAll();
        $this->response->response(HttpStatus::$OK, OfficeResponse::GET_ALL_OFFICES_SUCCESS->value, null, $offices);
    }

    /**
     * @return null
     * @throws ResponseException
     */
    public function delete()
    {
        $user_id = $this->request->getParam(ParamKeys::OFFICE_ID->value);
        $this->officeService->delete($user_id);
        $this->response->response(HttpStatus::$OK, OfficeResponse::DELETE_OFFICE_SUCCESS->value);
    }
}