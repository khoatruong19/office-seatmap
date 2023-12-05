<?php
declare( strict_types=1 );

namespace core;

abstract class Repository
{
    public function __construct(protected readonly Database $database)
    {
    }
}