<?php

declare(strict_types=1);

namespace core;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use shared\enums\GeneralResponse;
use shared\exceptions\ResponseException;
use shared\enums\RequestMethod;

class Router
{
    public Request $request;
    protected array $routes = [];

    public function __construct(private readonly ContainerInterface $container)
    {
        foreach (RequestMethod::cases() as $method) {
            $this->routes[strtolower($method->name)] = [];
        }
    }

    /**
     * @param RequestMethod $method
     * @param string $path
     * @param array|null $middlewares
     * @param $callback
     * @return void
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws ResponseException
     */
    public function addRoute(RequestMethod $method, string $path, ?array $middlewares, $callback): void
    {
        $route = $this->formatRoute($path);

        $this->routes[strtolower($method->name)][$route["path"]]["call_back"] = $callback;

        if (is_array($middlewares)) {
            foreach ($middlewares as $middleware) {
                if (class_exists($middleware)) {
                    $this->routes[strtolower($method->name)][$route["path"]]["middlewares"][] = $this->container->get(
                        $middleware
                    );
                } else {
                    throw new ResponseException(
                        HttpStatus::$INTERNAL_SERVER_ERROR,
                        GeneralResponse::MIDDLEWARE_NOT_FOUND->value
                    );
                }
            }
        }

        if (count($route["params"]) === 0) {
            return;
        }

        $this->routes[strtolower($method->name)][$route["path"]]["param_keys"] = $route["params"];
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return void
     * @throws ResponseException
     */
    public function resolve(Request $request, Response $response): void
    {
        $this->request = $request;
        $path = $this->request->getPath();
        $method = $this->request->getMethod();

        $result_matched_route = $this->matchRoute($method, $path) ?? false;

        if ($result_matched_route === false) {
            throw new ResponseException(HttpStatus::$NOT_FOUND, GeneralResponse::ROUTE_NOT_FOUND->value);
        }

        $callback = $result_matched_route["call_back"];
        $middlewares = $result_matched_route["middlewares"];

        if ($middlewares && count($middlewares) > 0) {
            foreach ($middlewares as $middleware) {
                $middleware->execute();
            }
        }

        if (is_array($callback)) {
            $this->container->call($callback);
        }
    }

    /**
     * @param $request_method
     * @param $path_incoming
     * @return array|void
     */
    private function matchRoute($request_method, $path_incoming)
    {
        foreach ($this->routes[$request_method] as $path => $pathValue) {
            if (preg_match($path, $path_incoming, $params_value)) {
                if (array_key_exists("param_keys", $this->routes[$request_method][$path])) {
                    $paramKeys = $this->routes[$request_method][$path]["param_keys"];
                    foreach ($paramKeys as $index => $param) {
                        $this->request->setParam($param, $params_value[$index + 1]);
                    }
                }

                return [
                    "call_back" => $this->routes[$request_method][$path]["call_back"],
                    "middlewares" => array_key_exists(
                        "middlewares",
                        $this->routes[$request_method][$path]
                    ) ? $this->routes[$request_method][$path]["middlewares"] : null,
                ];
            }
        }
    }

    /**
     * @param $path
     * @return array
     */
    private function formatRoute($path): array
    {
        $param_pattern = "~^\:(\w+)$~";
        $replace_pattern = "([\w-]+)";
        $path_component = explode("/", $path);
        $paramsKey = [];

        foreach ($path_component as $key => $value) {
            if (preg_match($param_pattern, $value, $matches)) {
                $path_component[$key] = $replace_pattern;
                $paramsKey[] = $matches[1];
            }
        }

        return
            [
                "path" => "~^" . implode("/", $path_component) . "$~",
                "params" => $paramsKey
            ];
    }
}