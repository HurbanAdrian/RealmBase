<?php
require_once __DIR__ . '/app/core/Database.php';
require_once __DIR__ . '/app/core/Router.php';
require_once __DIR__ . '/app/core/Controller.php';
require_once __DIR__ . '/app/core/View.php';
require_once __DIR__ . '/app/core/Database.php';
use App\core\Database;
use App\core\Router;

// Spustenie routera
$router = new Router();
$router->route($_SERVER['REQUEST_URI']);

$db = Database::connect();
echo "✅ Database connected successfully!";