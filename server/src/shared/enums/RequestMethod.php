<?php
    declare( strict_types=1 );

    namespace shared\enums;

    enum RequestMethod {
        case POST;
        case GET;
        case PUT;
        case PATCH;
        case DELETE;
    }