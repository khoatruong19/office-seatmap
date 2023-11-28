<?php

namespace core;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use shared\exceptions\ResponseException;
use shared\enums\RequestMethod;

class Router
{
    public Request $request;
    protected array $routes = [];
    protected array $param = [];

    public function __construct(private readonly ContainerInterface $container)
    {
        foreach (RequestMethod::cases() as $method) {
            $this->routes[strtolower($method->name)] = [];
        }
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function addRoute(RequestMethod $method, string $path, ?string $middleware, $callback): void
    {
        $route = $this->handleRoute($path);
        $this->routes[strtolower($method->name)][$route["path"]]["call_back"] = $callback;
        // echo "<pre>";
        // print_r($this->routes);
        // echo "</pre>";
        if ($middleware != null && class_exists($middleware)) {
            $this->routes[strtolower($method->name)][$route["path"]]["middleware"] = $this->container->get($middleware);
        }

        if (count($route["params"]) === 0) {
            return;
        }

        $this->routes[strtolower($method->name)][$route["path"]]["params_key"] = $route["params"];
        foreach ($route["params"] as $param) {
            $this->routes[strtolower($method->name)][$route["path"]][$param] = null;
        }
    }

    /**
     * @throws ResponseException
     */
    public function resolve(Request $request, Response $response)
    {
        $this->request = $request;
        $path = $this->request->getPath();
        $method = $this->request->getMethod();

        $resultMatchedRoute = $this->matchedRoute($method, $path) ?? false;

        if ($resultMatchedRoute === false) {
            throw new ResponseException(HttpStatus::$NOT_FOUND, "Not found");
        }

        $callback = $resultMatchedRoute["call_back"];
        $middleware = $resultMatchedRoute["middleware"];

        if($middleware) {
            $middleware->execute();
        }

        if (is_array($callback)) {
            return $this->container->call($callback);
        }
    }

    private function matchedRoute($requestMethod, $pathIncoming)
    {
        foreach ($this->routes[$requestMethod] as $path => $pathValue) {

            if (preg_match($path, $pathIncoming, $paramsValue)) {
                if (array_key_exists("params_key", $this->routes[$requestMethod][$path])) {
                    $paramsKey = $this->routes[$requestMethod][$path]["params_key"];

                    foreach ($paramsKey as $index => $param) {
                        $this->request->setParam($param, $paramsValue[$index + 1]);
                    }
                }

                return [
                    "call_back" => $this->routes[$requestMethod][$path]["call_back"],
                    "middleware" => array_key_exists("middleware", $this->routes[$requestMethod][$path]) ? $this->routes[$requestMethod][$path]["middleware"] : null,
                ];
            }
        }
    }

    private function handleRoute($path)
    {
        $pattern = "~^\{(\w+)\}$~";
        $replacePattern = "([\w-]+)";
        $pathComponent = explode("/", $path);

        $paramsKey = [];
        foreach ($pathComponent as $key => $value) {
            if (preg_match($pattern, $value, $matches)) {
                $pathComponent[$key] = $replacePattern;
                array_push($paramsKey, $matches[1]);
            }
        }

        echo implode(",", $paramsKey);

        return
            [
                "path" => "~^" . implode("/", $pathComponent) . "$~",
                "params" => $paramsKey
            ];
    }
}