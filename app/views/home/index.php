<?php
require_once __DIR__ . '/../app/core/Router.php';
require_once __DIR__ . '/../config/config.php';

use core\Router;

$router = new Router();
$router->route($_SERVER['REQUEST_URI']);