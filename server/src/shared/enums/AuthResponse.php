<?php
declare( strict_types=1 );

namespace shared\enums;

enum AuthResponse : string {
    case UNAUTHORIZED = "Not authorized!";
    case REGISTER_SUCCESS = "Register successfully!";
    case LOGIN_SUCCESS = "Login successfully!";
    case LOGOUT_SUCCESS = "Logout successfully!";
    case ME_SUCCESS = "Welcome back!";
    case INVALID_CREDENTIAL = "Invalid credential!";
}