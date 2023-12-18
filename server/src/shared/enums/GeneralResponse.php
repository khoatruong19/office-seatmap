<?php
declare( strict_types=1 );

namespace shared\enums;

enum GeneralResponse : string {
    case DATABASE_CONNECTION_FAIL = "Database cannot be connected due to wrong configuration!";
    case MIDDLEWARE_NOT_FOUND = "Middleware's not found!";
    case ROUTE_NOT_FOUND = "Route's not found!";

}