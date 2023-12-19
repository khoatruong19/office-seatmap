<?php

declare(strict_types=1);

namespace shared\interfaces;

interface IDto
{
    public static function fromArray(array $raw_data): mixed;
}