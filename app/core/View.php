<?php
namespace App\core;

class View {
    public static function render($view, $data = []) {
        extract($data);
        require_once "../app/views/$view.php";
    }
}
