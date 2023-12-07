<?php
declare( strict_types=1 );

namespace shared\utils;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

class Constant {
}