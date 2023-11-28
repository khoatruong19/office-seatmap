<?php

namespace core;

class Response {

    private $successResponse = array();
    public function setStatusCode(int $statusCode) {
        return http_response_code($statusCode);
    }

    public function __construct()
    {
        $this->successResponse = [HttpStatus::$OK, HttpStatus::$CREATED];
    }

    public function response($httpStatusCode, $message, $data = null) {
        header('Content-Type: application/json');
        http_response_code($httpStatusCode);

        $response['statusCode'] = $httpStatusCode;
        $response['messages'] = $message;
        if($data) $response['data'] = $data;

        echo json_encode($response);
        die;
    }
}