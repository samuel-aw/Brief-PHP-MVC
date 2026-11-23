<?php

namespace App\Core;

class Router {
    private array $routes = [];

    public function get(string $path, array $handler): void {
        $this->routes['GET'][$path] = $handler;
    }

    public function post(string $path, array $handler): void {
        $this->routes['POST'][$path] = $handler;
    }

    public function dispatch(string $method, string $uri): void {
        $path = parse_url($uri, PHP_URL_PATH) ?: '/';
        $path = rtrim($path, '/') ?: '/';

        $handler = $this->routes[$method][$path] ?? null;

        if ($handler === null) {
            $this->renderError($path);
            return;
        }

        [$controllerClass, $action] = $handler;

        $controller = new $controllerClass();
        $controller->$action();
    }

    private function renderError(string $path): void {
        $pathExists = false;
        foreach ($this->routes as $routes) {
            if (isset($routes[$path])) {
                $pathExists = true;
            }
        }

        $code = $pathExists ? 405 :404;
        http_response_code($code);

        View::render('errors/error', [
            'pageTitle' => 'Erreur' . $code,
            'code' => $code,
            'message' => $pathExists ? 'Méthode non autorisée' : 'Page introuvable',
        ]);
    }
}