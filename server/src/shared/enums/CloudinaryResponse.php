<?php

declare(strict_types=1);

namespace shared\enums;

enum CloudinaryResponse: string
{
    case FILE_TOO_LARGE = "File too large!";
    case FILE_WRONG_FORMAT = "File wrong format!";
    case UPLOAD_FILE_FAIL = "Upload file failed!";
    case DELETE_FILE_FAIL = "Delete file failed!";
}