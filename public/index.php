<?php
require_once __DIR__ . '/app/core/Database.php';
use App\core\Database;

$db = Database::connect();
echo "✅ Database connected successfully!";