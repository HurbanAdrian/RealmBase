<?php
namespace App\core;

use PDO;
use PDOException;

class Database {
    private static ?PDO $connection = null;

    public static function connect(): PDO {
        if (self::$connection === null) {
            $config = require __DIR__ . '/../../config/database.php';

            try {
                $dsn = "mysql:host={$config['host']};dbname={$config['database']};charset=utf8mb4";
                self::$connection = new PDO($dsn, $config['user'], $config['password']);
                self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Database connection failed: " . $e->getMessage());
            }
        }
        return self::$connection;
    }
}
