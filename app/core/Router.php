<?php
namespace core;
class Router {
    public function route($uri) {
        $uri = trim(parse_url($uri, PHP_URL_PATH), '/');
        if ($uri === '') $uri = 'home';

        $parts = explode('/', $uri);
        $controllerName = ucfirst($parts[0]) . 'Controller';
        $method = $parts[1] ?? 'index';

        $controllerPath = __DIR__ . '/../controllers/' . $controllerName . '.php';
        if (file_exists($controllerPath)) {
            require_once $controllerPath;
            $controllerClass = 'App\\Controllers\\' . $controllerName;
            $controller = new $controllerClass();
            if (method_exists($controller, $method)) {
                $controller->$method();
            } else {
                $this->error404();
            }
        } else {
            $this->error404();
        }
    }

    private function error404() {
        http_response_code(404);
        include __DIR__ . '/../views/errors/404.php';
    }
}