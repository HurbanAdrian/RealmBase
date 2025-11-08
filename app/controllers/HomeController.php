<?php
namespace App\controllers;

use app\core\Controller;

class HomeController extends Controller {
    public function index() {
        $data = ['title' => 'RealmBase – Komunitné RPG fórum'];
        $this->view('home/index', $data);
    }

    public function about() {
        $data = ['title' => 'O projekte RealmBase'];
        $this->view('home/about', $data);
    }
}
