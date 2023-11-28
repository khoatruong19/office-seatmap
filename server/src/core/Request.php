<?php

namespace core;

use app\middlewares\ValidationIncomingData;
use shared\enums\RequestMethod;
use core\Validation;

class Request
{
    protected $params = array();
    protected $queries = array();

    public function __construct(public Validation $validation)
    {

    }

    private function extractQuery($path): void
    {
        $position = strpos($path, '?');
        $queriesString = substr($path, $position + 1);

        $queriesComponent = explode("&", $queriesString);
        foreach ($queriesComponent as $value) {
            $pair = explode("=", $value);
            $this->queries[$pair[0]] = $pair[1];
        }
    }

    public function getMethod(): string
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    public function getPath()
    {
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        $position = strpos($path, '?');

        if ($position === false) {
            return $path;
        }

        $this->extractQuery($path);
        return substr($path, 0, $position);
    }

    public function getBody()
    {
        $body = json_decode(file_get_contents('php://input'), true);
        return $body;
    }

    public function validateBody(array $fieldRules)
    {
        $body = $this->getBody();
        foreach ($fieldRules as $key => $value) {
            $rules = explode('|', $value);

            foreach($rules as $rule) { 
                $ruleArray = explode(':', $rule);
                $ruleMethod = $ruleArray[0];
                $ruleMethodParameter;

                if(count($ruleArray) > 1){
                    $ruleMethodParameter = $ruleArray[1];
                }

                $this->validation->name($key)->value(isset($body[$key]) ? $body[$key] : "");
                if(isset($ruleMethodParameter)){
                    call_user_func(array($this->validation, $ruleMethod), $ruleMethodParameter);
                }
                else{
                    call_user_func(array($this->validation, $ruleMethod));
                }

                if(!$this->validation->isSuccess()) break;
            }
        }
        return $this->validation->getErrors();
    }

    public function setParam($key, $value)
    {
        $this->params[$key] = $value;
    }

    public function getParam($key)
    {
        return $this->params[$key];
    }

    public function getQuery($key)
    {
        return $this->queries[$key];
    }

    public function getQueries()
    {
        return $this->queries;
    }

    public function getParams()
    {
        return $this->params;
    }
}
