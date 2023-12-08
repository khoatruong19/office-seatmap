<?php
declare(strict_types=1);

namespace core;

use shared\enums\RequestMethod;
use core\Validation;

class Request
{
    protected array $params = [];
    protected array $queries = [];

    public function __construct(public Validation $validation)
    {

    }

    /**
     * @param $path
     * @return void
     */
    private function extractQuery($path): void
    {
        $position = strpos($path, '?');
        $queries_string = substr($path, $position + 1);

        $queries_parts = explode("&", $queries_string);
        foreach ($queries_parts as $value) {
            $pair = explode("=", $value);
            $this->queries[$pair[0]] = $pair[1];
        }
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        $position = strpos($path, '?');

        if ($position === false) {
            return $path;
        }

        $this->extractQuery($path);
        return substr($path, 0, $position);
    }

    /**
     * @return array
     */
    public function getBody(): array
    {
        $body = json_decode(file_get_contents('php://input'), true);

        if (!is_array($body)) {
            $body = [];
            switch (strtoupper($this->getMethod())) {
                case RequestMethod::POST->name:
                    foreach ($_POST as $key => $value) {
                        $body[$key] = filter_input(INPUT_POST, $key, FILTER_DEFAULT);
                    }
                    break;
                default:
                    break;
            }
        }

        return $body;
    }

    /**
     * @param array $field_rules
     * @return array|null
     */
    public function validateBody(array $field_rules): array | null
    {
        $body = $this->getBody();

        //check redundant fields
        foreach ($body as $field => $value) {
            if (!isset($field_rules[$field])) return $this->validation->redundantFieldErrors();
        }

        foreach ($field_rules as $field => $value) {
            if($value == "") continue;

            $rules = explode('|', $value);
            foreach ($rules as $rule) {
                $rule_array = explode(':', $rule);
                $rule_method = $rule_array[0];
                if (count($rule_array) > 1) {
                    $rule_method_parameter = $rule_array[1];
                }

                $this->validation->name($field)->value($body[$field] ?? "");
                if (isset($rule_method_parameter) && $rule_method_parameter != "") {
                    call_user_func(array($this->validation, $rule_method), $rule_method_parameter);
                } else {
                    call_user_func(array($this->validation, $rule_method));
                }

                if (!$this->validation->isSuccess()) break;
            }
        }
        return $this->validation->getErrors();
    }

    /**
     * @param $key
     * @param $value
     * @return void
     */
    public function setParam($key, $value): void
    {
        $this->params[$key] = $value;
    }

    /**
     * @param $key
     * @return mixed
     */
    public function getParam($key): mixed
    {
        return $this->params[$key];
    }

    public function getIntParam($key): int
    {
        return intval($this->params[$key]);
    }

    /**
     * @param $key
     * @return mixed
     */
    public function getQuery($key): mixed
    {
        return $this->queries[$key];
    }

    /**
     * @return array
     */
    public function getQueries(): array
    {
        return $this->queries;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }
}
