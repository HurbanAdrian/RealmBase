<?php
require_once __DIR__ . '/app/core/Database.php';
use Core\Database;

$db = Database::connect();
echo "✅ Database connected successfully!";