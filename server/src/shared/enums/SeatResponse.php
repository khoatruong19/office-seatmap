<?php

declare(strict_types=1);

namespace shared\enums;

enum SeatResponse: string
{
    case CREATE_OFFICE_SUCCESS = "Create office successfully!";
    case SET_USER_SUCCESS = "Set user to seat successfully!";
    case REMOVE_USER_SUCCESS = "Remove user from seat successfully!";
    case SWAP_USERS_SUCCESS = "Swap users successfully!";
    case NOT_FOUND = "Seat not found!";

}