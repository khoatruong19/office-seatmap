<?php
    declare( strict_types=1 );
    
    namespace core;

    #[\AllowDynamicProperties]
    class Validation {
        public $patterns = array(
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
      
        public $errors = array();
        
        public function name($name){
            $this->name = $name;
            return $this;
        }
        
        public function value($value){
            $this->value = $value;
            return $this;
        }
        
        public function pattern($name){
            $regex = '/^('.$this->patterns[$name].')$/u';
            if($this->value != '' && !preg_match($regex, $this->value)){
                $this->errors[] = 'Field '.$this->name.' is not valid.';
            }
            return $this;
        }
        
        public function customPattern($pattern){
            echo $this->value;
            $regex = '/^('.$pattern.')$/u';
            if($this->value != '' && !preg_match($regex, $this->value)){
                $this->errors[] = 'Field %'.$this->name.'% is not valid.';
            }
            return $this;
        }
        
        public function required(){
            if(($this->value == '' || $this->value == null)){
                $this->errors[] = 'Field %'.$this->name.'% is required.';
            }            
            return $this;
        }

        public function min($length){
            if(strlen($this->value) > 0 && strlen($this->value) < $length){
                $this->errors[] = 'Field %'.$this->name.'% has to be at least '.$length.' characters';
            }
            return $this;
        }
            
        public function max($length){
            if(strlen($this->value) > (int)$length){
                $this->errors[] = 'Field %'.$this->name.'% has to be less than '.$length.' characters';
            }
            return $this;
        }
        
        public function isSuccess(){
            if(empty($this->errors)) return true;
        }
        
        public function getErrors(){
            if(!$this->isSuccess()) return $this->errors;
        }
        
        public function redundantFieldErrors(){
            $this->errors[] = 'There are some redundant fields in the body!';
            return $this->errors;
        }

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
        
        public static function is_int($value){
            if(filter_var($value, FILTER_VALIDATE_INT)) return true;
        }
        
        public static function is_float($value){
            if(filter_var($value, FILTER_VALIDATE_FLOAT)) return true;
        }
        
        public static function is_alpha($value){
            if(filter_var($value, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => "/^[a-zA-Z]+$/")))) return true;
        }
        
        public static function is_alphanum($value){
            if(filter_var($value, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => "/^[a-zA-Z0-9]+$/")))) return true;
        }
        
        public static function is_url($value){
            if(filter_var($value, FILTER_VALIDATE_URL)) return true;
        }
        
        public static function is_uri($value){
            if(filter_var($value, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => "/^[A-Za-z0-9-\/_]+$/")))) return true;
        }
        
        public static function is_bool($value){
            if(is_bool(filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE))) return true;
        }
        
        public static function is_email($value){
            if(filter_var($value, FILTER_VALIDATE_EMAIL)) return true;
        }

        public function resetErrors()
        {
            $this->error = array();
        }        
    }