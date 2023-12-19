<?php

declare(strict_types=1);

namespace shared\enums;

enum OfficeResponse: string
{
    case CREATE_OFFICE_SUCCESS = "Create office successfully!";
    case UPDATE_OFFICE_SUCCESS = "Update office successfully!";
    case GET_ALL_OFFICES_SUCCESS = "Get all offices successfully!";
    case GET_ONE_OFFICE_SUCCESS = "Get office successfully!";
    case DELETE_OFFICE_SUCCESS = "Delete office successfully!";
    case DELETE_OFFICE_FAIL = "Delete office fail!";
    case OFFICE_NAME_EXISTS = "Office's name existed!";
    case NOT_FOUND = "Office not found!";

}