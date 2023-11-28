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
        $body = [];

        switch (strtoupper($this->getMethod())) {
            case RequestMethod::POST->name:
                foreach ($_POST as $key => $value) {
                    $body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                }
                break;
            default:
                break;
        }

        return $body;
    }

    public function validateBody(array $fieldRules)
    {
        var_dump($this->validation);

        $mapRuleToValidationMethod = array(
            'required' => $this->validation->required
        );
        $email = "";

        foreach ($fieldRules as $key => $value) {
            $rules = explode('|', $value);
            
            foreach($rules as $rule) { 
                $this->validation->name($key)->value($email);

            }
            var_dump($this->validation->getErrors());            // echo $value;
        }
        return "sd";
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
