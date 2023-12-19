<?php

declare(strict_types=1);

namespace shared\interfaces;

interface IMiddleware
{
    public function execute(): bool;
}