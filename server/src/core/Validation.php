<?php
declare( strict_types=1 );

namespace core;

#[\AllowDynamicProperties]
class Validation {
    public array $patterns = array(
        'uri'           => '[A-Za-z0-9-\/_?&=]+',
        'url'           => '[A-Za-z0-9-:.\/_?&=#]+',
        'alpha'         => '[\p{L}]+',
        'words'         => '[\p{L}\s]+',
        'alphanum'      => '[\p{L}0-9]+',
        'int'           => '[0-9]+',
        'float'         => '[0-9\.,]+',
        'tel'           => '[0-9+\s()-]+',
        'text'          => '[\p{L}0-9\s-.,;:!"%&()?+\'°#\/@]+',
        'file'          => '[\p{L}\s0-9-_!%&()=\[\]#@,.;+]+\.[A-Za-z0-9]{2,4}',
        'folder'        => '[\p{L}\s0-9-_!%&()=\[\]#@,.;+]+',
        'address'       => '[\p{L}0-9\s.,()°-]+',
        'date_dmy'      => '[0-9]{1,2}\-[0-9]{1,2}\-[0-9]{4}',
        'date_ymd'      => '[0-9]{4}\-[0-9]{1,2}\-[0-9]{1,2}',
        'email'         => '[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$'
    );

    public array $errors = array();

    /**
     * @param $name
     * @return $this
     */
    public function name($name): static
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param $value
     * @return $this
     */
    public function value($value): static
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @param $name
     * @return $this
     */
    public function pattern($name): static
    {
        $regex = '/^('.$this->patterns[$name].')$/u';
        if($this->value != '' && !preg_match($regex, $this->value)){
            $this->errors[] = 'Field '.$this->name.' is not valid.';
        }
        return $this;
    }

    /**
     * @param $pattern
     * @return $this
     */
    public function customPattern($pattern): static
    {
        echo $this->value;
        $regex = '/^('.$pattern.')$/u';
        if($this->value != '' && !preg_match($regex, $this->value)){
            $this->errors[] = 'Field %'.$this->name.'% is not valid.';
        }
        return $this;
    }

    /**
     * @return $this
     */
    public function required(): static
    {
        if(($this->value == '' || $this->value == null)){
            $this->errors[] = 'Field %'.$this->name.'% is required.';
        }
        return $this;
    }

    /**
     * @param $length
     * @return $this
     */
    public function min($length): static
    {
        if(strlen($this->value) > 0 && strlen($this->value) < $length){
            $this->errors[] = 'Field %'.$this->name.'% has to be at least '.$length.' characters';
        }
        return $this;
    }

    /**
     * @param $length
     * @return $this
     */
    public function max($length): static
    {
        if(strlen($this->value) > (int)$length){
            $this->errors[] = 'Field %'.$this->name.'% has to be less than '.$length.' characters';
        }
        return $this;
    }

    /**
     * @return true|void
     */
    public function isSuccess(){
        if(empty($this->errors)) return true;
    }

    /**
     * @return array|void
     */
    public function getErrors(){
        if(!$this->isSuccess()) return $this->errors;
    }

    /**
     * @return array
     */
    public function redundantFieldErrors(): array
    {
        $this->errors[] = 'There are some redundant fields in the body!';
        return $this->errors;
    }

    /**
     * @return true|void
     */
    public function result(){
        if(!$this->isSuccess()){
            foreach($this->getErrors() as $error){
                echo "$error\n";
            }
            exit;

        }else{
            return true;
        }
    }

    /**
     * @param $value
     * @return true|void
     */
    public static function is_int($value){
        if(filter_var($value, FILTER_VALIDATE_INT)) return true;
    }

    /**
     * @param $value
     * @return true|void
     */
    public static function is_float($value){
        if(filter_var($value, FILTER_VALIDATE_FLOAT)) return true;
    }

    /**
     * @param $value
     * @return true|void
     */
    public static function is_alpha($value){
        if(filter_var($value, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => "/^[a-zA-Z]+$/")))) return true;
    }

    /**
     * @param $value
     * @return true|void
     */
    public static function is_alphanum($value){
        if(filter_var($value, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => "/^[a-zA-Z0-9]+$/")))) return true;
    }

    /**
     * @param $value
     * @return true|void
     */
    public static function is_url($value){
        if(filter_var($value, FILTER_VALIDATE_URL)) return true;
    }

    /**
     * @param $value
     * @return true|void
     */
    public static function is_uri($value){
        if(filter_var($value, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => "/^[A-Za-z0-9-\/_]+$/")))) return true;
    }

    /**
     * @param $value
     * @return true|void
     */
    public static function is_bool($value){
        if(is_bool(filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE))) return true;
    }

    /**
     * @param $value
     * @return true|void
     */
    public static function is_email($value){
        if(filter_var($value, FILTER_VALIDATE_EMAIL)) return true;
    }

    /**
     * @return void
     */
    public function resetErrors()
    {
        $this->error = array();
    }
}