<?php

namespace shared\exceptions;

use core\HttpStatus;
use Throwable;

class ResponseException extends \Exception implements Throwable
{
    private int $httpStatus;
    public function __construct(int $httpStatus, string $message)
    {
        $this->httpStatus = $httpStatus;
        $this->message = $message;
    }

    public function getHttpStatus(): int
    {
        return $this->httpStatus;
    }
}