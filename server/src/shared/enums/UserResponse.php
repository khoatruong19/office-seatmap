<?php
declare( strict_types=1 );

namespace shared\enums;

enum UserResponse : string {
    case GET_ALL_SUCCESS = "Get all users successfuly!";
    case CREATE_USER_SUCCESS = "Create user successfully!";
    case UPDATE_USER_SUCCESS = "Update user successfully!";
    case DELETE_USER_SUCCESS = "Delete user successfully!";
    case UPDATE_PROFILE_SUCCESS = "Update profile successfully!";
    case UPLOAD_SUCCESS = "Upload successfully!";
    case GET_ALL_FAIL = "Get all users failed!";
    case CREATE_USER_FAIL = "Create user failed!";
    case UPDATE_USER_FAIL = "Update user failed!";
    case DELETE_USER_FAIL = "Delete user failed!";
    case UPDATE_PROFILE_FAIL = "Update profile failed!";
    case UPLOAD_FAIL = "Upload failed!";
    case NOT_FOUND = "User's not found!";
    case NO_FILE_FOUND = "No file found!";
    case NO_PERMISSION = "No permission!";
    case EMAIL_EXISTS = "Email's existed!";

}