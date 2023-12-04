<?php
    declare( strict_types=1 );

    namespace shared\enums;

    enum UserRole : string {
        case USER = "user";
        case ADMIN = "admin";
    }