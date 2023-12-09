<?php
declare( strict_types=1 );

namespace shared\enums;

enum SeatResponse : string {
    case CREATE_OFFICE_SUCCESS = "Create office successfully!";
    case SET_USER_SUCCESS = "Set user to seat successfully!";
    case REMOVE_USER_SUCCESS = "Remove user from seat successfully!";
    case GET_ALL_OFFICES_SUCCESS = "Get all offices successfully!";
    case GET_ONE_OFFICE_SUCCESS = "Get office successfully!";
    case DELETE_OFFICE_SUCCESS = "Delete office successfully!";
    case DELETE_OFFICE_FAIL = "Delete office fail!";
    case OFFICE_NAME_EXISTS = "Office's name existed!";
    case NOT_FOUND = "Office not found!";

}