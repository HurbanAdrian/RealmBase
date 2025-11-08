<?php
namespace App\core;

class Controller {
    public function view($view, $data = []) {
        // rozbalí asociatívne pole na samostatné premenné
        extract($data);
        require __DIR__ . '/../views/' . $view . '.php';
    }
}