<?php

namespace core;

class Controller
{
    public Request $request;
    public Response $response;
    public function __construct()
    {
        $this->request = $GLOBALS['request'];
        $this->response = $GLOBALS['response'];
    }

    public function checkBodyValidationError($errors){
        if(is_array($errors) && count($errors) > 0)
         return $this->response->response(HttpStatus::$BAD_REQUEST, $errors);
    }
}
