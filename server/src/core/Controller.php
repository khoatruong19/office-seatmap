<?php
declare( strict_types=1 );

namespace core;

use shared\enums\ValidationResponse;
use shared\exceptions\ResponseException;

class Controller
{
    public Request $request;
    public Response $response;
    public function __construct()
    {
        $this->request = $GLOBALS['request'];
        $this->response = $GLOBALS['response'];
    }

    /**
     * @param array $body_schema
     * @return void
     */
    public function requestBodyValidation(array $body_schema)
    {
        $errors = $this->request->validateBody($body_schema);
        if(is_array($errors) && count($errors) > 0) {
            throw new ResponseException(HttpStatus::$BAD_REQUEST, ValidationResponse::INVALID_FIELDS->value, $errors);
        }
    }
}
