<?php

class Router
{
    private $routes = [];

    public function addRoute($uri, $controller)
    {
        $this->routes[$uri] = $controller;
    }

    public function handleRequest($uri)
    {

        if (array_key_exists($uri, $this->routes)) {
            $controller = $this->routes[$uri];
            $controller->handle();
        } else {

            (new HomeController())->handle();
        }
    }
}
