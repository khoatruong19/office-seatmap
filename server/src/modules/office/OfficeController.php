<?php
declare( strict_types=1 );

namespace modules\office;

use core\HttpStatus;
use core\Request;
use core\Response;
use core\Controller;
use modules\office\OfficeService;
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
            'name' => 'required|min:4|max:60',
        ]);

        $request_body = $this->request->getBody();

        $id = $this->officeService->create($request_body);

        $this->response->response(HttpStatus::$OK, "Create office successfully!", $id, null);
    }
}