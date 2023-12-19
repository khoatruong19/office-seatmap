<?php

declare(strict_types=1);

namespace shared\exceptions;

use Throwable;

class ResponseException extends \Exception implements Throwable
{
    private int $http_status;
    private mixed $errors;

    public function __construct(int $http_status, string $message, mixed $errors = null)
    {
        $this->http_status = $http_status;
        $this->message = $message;
        $this->errors = $errors;
    }

    /**
     * @return int
     */
    public function getHttpStatus(): int
    {
        return $this->http_status;
    }

    public function getErrors(): mixed
    {
        return $this->errors;
    }
}