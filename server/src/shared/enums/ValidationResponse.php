<?php

declare(strict_types=1);

namespace shared\enums;

enum ValidationResponse: string
{
    case  INVALID_FIELDS = "Some fields are not valid!";

}