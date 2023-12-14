<?php
declare( strict_types=1 );

namespace shared\helpers;
use core\HttpStatus;
use shared\enums\RepositoryResponse;
use shared\exceptions\ResponseException;

class ValidationErrorMessage  {
    /**
     * @param string $field
     * @return string
     */
    public static function fieldIsNotValid(string $field){
        return sprintf('Field "%s" is not valid!', $field);
    }

    /**
     * @param string $field
     * @return string
     */
    public static function fieldIsRequired(string $field){
        return sprintf('Field "%s" is required!', $field);
    }

    /**
     * @param string $field
     * @param int $length
     * @return string
     */
    public static function minLengthCharacters(string $field, int $length){
        return sprintf('Field "%s" has to be at least "%d" characters!', $field, $length);
    }

    /**
     * @param string $field
     * @param int $length
     * @return string
     */
    public static function maxLengthCharacters(string $field, int $length){
        return sprintf('Field "%s" has to be less than "%d" characters!', $field, $length);
    }

    /**
     * @param string $field
     * @param int $length
     * @return string
     */
    public static function redundantFields(){
        return "There are some redundant fields in the body!";
    }
}