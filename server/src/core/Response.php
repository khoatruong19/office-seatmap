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

    public function response($httpStatusCode, $data) {
        header('Content-Type: application/json');
        http_response_code($httpStatusCode);

        if(in_array($httpStatusCode, $this->successResponse)) {
            $response = $data;
        } else {
            $response['statusCode'] = $httpStatusCode;
            $response['message'] = $data;
        }

        echo json_encode($response);
        die;
    }
}