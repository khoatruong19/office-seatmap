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
}
