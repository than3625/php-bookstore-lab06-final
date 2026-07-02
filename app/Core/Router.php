<?php

class Router
{
    private $routes = [];

    public function __construct(array $routes) {
        $this->routes = $routes;
    }

    public function get(string $path, array $handler): void
    {
        $this->routes['GET'][$path] = $handler;
    }

    public function post(string $path, array $handler): void
    {
        $this->routes['POST'][$path] = $handler;
    }

    public function dispatch(string $method, string $uri): void
    {
        $path = parse_url($uri, PHP_URL_PATH) ?: '/';

        if(isset($this->routes[$method][$path])){
            [$controller, $action] = $this->routes[$method][$path];
            (new $controller())->$action();
            return;
        }

        foreach ($this->routes as $methodRoutes) {
            if (isset($methodRoutes[$path])) {
                http_response_code(405);
                render('errors/405', ['title' => 'Method Not Allowed']);
                return;
            }
        }
        http_response_code(404);
        render('errors/404', ['title' => 'Page Not Found']);
    }
}