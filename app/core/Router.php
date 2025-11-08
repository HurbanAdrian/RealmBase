<?php
namespace App\core;

class Router {
    public function route($uri = null) {
        // Ak je zapnutý mod_rewrite, môžeme používať $_GET['url']
        if ($uri === null && isset($_GET['url'])) {
            $uri = $_GET['url'];
        }

        $uri = trim(parse_url($uri ?? '', PHP_URL_PATH), '/');
        if ($uri === '') $uri = 'home/index';

        $parts = explode('/', $uri);
        $controllerName = ucfirst($parts[0]) . 'Controller';
        $method = $parts[1] ?? 'index';
        $params = array_slice($parts, 2);

        $controllerPath = __DIR__ . '/../controllers/' . $controllerName . '.php';

        if (file_exists($controllerPath)) {
            require_once $controllerPath;
            $controllerClass = 'App\\Controllers\\' . $controllerName;
            $controller = new $controllerClass();

            if (method_exists($controller, $method)) {
                call_user_func_array([$controller, $method], $params);
            } else {
                $this->error404("Metóda '$method' neexistuje v $controllerClass");
            }
        } else {
            $this->error404("Controller '$controllerName' nebol nájdený");
        }
    }

    private function error404($msg = '') {
        http_response_code(404);
        $errorMessage = $msg ?: 'Stránka nebola nájdená.';
        include __DIR__ . '/../views/errors/404.php';
    }
}
