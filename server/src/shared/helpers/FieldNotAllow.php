<?php

declare(strict_types=1);

namespace shared\helpers;

use core\HttpStatus;
use shared\enums\RepositoryResponse;
use shared\exceptions\ResponseException;

class FieldNotAllow
{
    /**
     * @param array $allowed_fields
     * @param string $field
     * @return void
     * @throws ResponseException
     */
    public static function execute(array $allowed_fields, string $field): void
    {
        if (!in_array($field, $allowed_fields)) {
            throw new ResponseException(HttpStatus::$BAD_REQUEST, RepositoryResponse::FIELD_NOT_ALLOW->value);
        }
    }
}