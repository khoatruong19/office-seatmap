<?php
declare( strict_types=1 );

namespace shared\enums;

enum SessionKeys : string {
    case USER_ID = "userId";
    case USER_ROLE = "role";
}