<?php
declare( strict_types=1 );

namespace core;

class Response {
    public function setStatusCode(int $statusCode): bool|int
    {
        return http_response_code($statusCode);
    }
    public function __construct()
    {
    }

    /**
     * @param $http_status_code
     * @param $message
     * @param $data
     * @return void
     */
    public function response($http_status_code, $message, mixed $errors = null, mixed $data = null,): void
    {
        header('Content-Type: application/json');
        http_response_code($http_status_code);

        $response['statusCode'] = $http_status_code;
        $response['messages'] = $message;
        if($data) $response['data'] = $data;
        if($errors) $response['errors'] = $errors;

        echo json_encode($response);
    }

}