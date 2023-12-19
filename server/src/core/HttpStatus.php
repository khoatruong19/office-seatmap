<?php

declare(strict_types=1);

namespace core;

class HttpStatus
{
    public static int $OK = 200;
    public static int $CREATED = 201;
    public static int $BAD_REQUEST = 400;
    public static int $NOT_FOUND = 404;
    public static int $INTERNAL_SERVER_ERROR = 500;
    public static int $UNAUTHORIZED = 401;
    public static int $FORBIDDEN = 403;
}