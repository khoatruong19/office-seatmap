<?php
declare( strict_types=1 );

namespace shared\enums;

enum EnumTypeJwt {
    case ACCESS_TOKEN;
    case REFRESH_TOKEN;
}