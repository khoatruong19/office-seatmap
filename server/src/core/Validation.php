<?php

declare(strict_types=1);

namespace core;

use shared\helpers\ValidationErrorMessage;

#[\AllowDynamicProperties]
class Validation
{
    public array $patterns = [
        'uri' => '[A-Za-z0-9-\/_?&=]+',
        'url' => '[A-Za-z0-9-:.\/_?&=#]+',
        'alpha' => '[\p{L}]+',
        'words' => '[\p{L}\s]+',
        'alphanum' => '[\p{L}0-9]+',
        'int' => '[0-9]+',
        'float' => '[0-9\.,]+',
        'tel' => '[0-9+\s()-]+',
        'text' => '[\p{L}0-9\s-.,;:!"%&()?+\'°#\/@]+',
        'file' => '[\p{L}\s0-9-_!%&()=\[\]#@,.;+]+\.[A-Za-z0-9]{2,4}',
        'folder' => '[\p{L}\s0-9-_!%&()=\[\]#@,.;+]+',
        'address' => '[\p{L}0-9\s.,()°-]+',
        'date_dmy' => '[0-9]{1,2}\-[0-9]{1,2}\-[0-9]{4}',
        'date_ymd' => '[0-9]{4}\-[0-9]{1,2}\-[0-9]{1,2}',
        'email' => '[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$',
        'bool' => '/^(?:true|false)$/igm'
    ];

    public array $errors = [];

    /**
     * @param string $name
     * @return $this
     */
    public function name(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param mixed $value
     * @return $this
     */
    public function value(mixed $value): static
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function pattern(string $name): static
    {
        $regex = '/^(' . $this->patterns[$name] . ')$/u';
        if ($this->value != '' && !preg_match($regex, $this->value)) {
            $this->errors[] = ValidationErrorMessage::fieldIsNotValid($this->name);
        }
        return $this;
    }

    /**
     * @param $pattern
     * @return $this
     */
    public function customPattern($pattern): static
    {
        $regex = '/^(' . $pattern . ')$/u';
        if ($this->value != '' && !preg_match($regex, $this->value)) {
            $this->errors[] = ValidationErrorMessage::fieldIsNotValid($this->name);
        }
        return $this;
    }

    /**
     * @return $this
     */
    public function required(): static
    {
        if (($this->value == '' || $this->value == null)) {
            $this->errors[] = ValidationErrorMessage::fieldIsRequired($this->name);
        }
        return $this;
    }

    /**
     * @param mixed $length
     * @return $this
     */
    public function min(mixed $length): static
    {
        if (strlen($this->value) > 0 && strlen($this->value) < (int)$length) {
            $this->errors[] = ValidationErrorMessage::minLengthCharacters($this->name, (int)$length);
        }
        return $this;
    }

    /**
     * @param mixed $length
     * @return $this
     */
    public function max(mixed $length): static
    {
        if (strlen($this->value) > (int)$length) {
            $this->errors[] = ValidationErrorMessage::maxLengthCharacters($this->name, (int)$length);
        }
        return $this;
    }

    /**
     * @return true|void
     */
    public function isSuccess()
    {
        if (empty($this->errors)) {
            return true;
        }
    }

    /**
     * @return array|void
     */
    public function getErrors()
    {
        if (!$this->isSuccess()) {
            return $this->errors;
        }
    }

    /**
     * @return array
     */
    public function redundantFieldErrors(): array
    {
        $this->errors[] = ValidationErrorMessage::redundantFields();
        return $this->errors;
    }

    /**
     * @return true|void
     */
    public function result()
    {
        if (!$this->isSuccess()) {
            foreach ($this->getErrors() as $error) {
                echo "$error\n";
            }
            exit;
        } else {
            return true;
        }
    }

    /**
     * @param mixed $value
     * @return true|void
     */
    public static function is_int(mixed $value)
    {
        if (filter_var($value, FILTER_VALIDATE_INT)) {
            return true;
        }
    }

    /**
     * @param mixed $value
     * @return true|void
     */
    public static function is_float(mixed $value)
    {
        if (filter_var($value, FILTER_VALIDATE_FLOAT)) {
            return true;
        }
    }

    /**
     * @param mixed $value
     * @return true|void
     */
    public static function is_alpha(mixed $value)
    {
        if (filter_var($value, FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => "/^[a-zA-Z]+$/"]])) {
            return true;
        }
    }

    /**
     * @param mixed $value
     * @return true|void
     */
    public static function is_alphanum(mixed $value)
    {
        if (filter_var($value, FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => "/^[a-zA-Z0-9]+$/"]])) {
            return true;
        }
    }

    /**
     * @param mixed $value
     * @return true|void
     */
    public static function is_url(mixed $value)
    {
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return true;
        }
    }

    /**
     * @param mixed $value
     * @return true|void
     */
    public static function is_uri(mixed $value)
    {
        if (filter_var($value, FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => "/^[A-Za-z0-9-\/_]+$/"]])) {
            return true;
        }
    }

    /**
     * @param mixed $value
     * @return true|void
     */
    public static function is_bool(mixed $value)
    {
        if (is_bool(filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE))) {
            return true;
        }
    }

    /**
     * @param mixed $value
     * @return true|void
     */
    public static function is_email(mixed $value)
    {
        if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return true;
        }
    }
}