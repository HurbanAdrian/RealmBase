<?php

namespace App\Controllers;

use App\Models\Category;
use Framework\Core\BaseController;
use Framework\Http\Request;
use Framework\Http\Responses\Response;

// Implementované s asistenciou AI

class CategoriesController extends BaseController
{
    public function index(Request $request): Response
    {
        $categories = Category::getAll();
        return $this->html(['categories' => $categories], 'index');
    }

    public function add(Request $request): Response
    {
        // 1. OCHRANA: Iba admin
        $user = $this->app->getAppUser();
        if (!$user || !$user->isLoggedIn() || $user->getRole() !== 'admin') {
            return $this->redirect($this->url('categories.index'));
        }

        // Ošetrenie vstupov (trim)
        $name = trim($request->post('name') ?? '');
        $description = trim($request->post('description') ?? '');
        $errors = [];

        if ($request->isPost()) {
            // --- VALIDÁCIA ---
            if (mb_strlen($name) < 3) {
                $errors[] = "Názov musí mať aspoň 3 znaky";
            }
            if (mb_strlen($name) > 50) { // Ochrana DB (zvyčajne VARCHAR 50)
                $errors[] = "Názov je príliš dlhý (max 50 znakov)";
            }
            if (mb_strlen($description) > 255) { // Ochrana DB
                $errors[] = "Popis je príliš dlhý (max 255 znakov)";
            }

            if (empty($errors)) {
                $category = new Category();
                $category->setName($name);
                $category->setDescription($description);
                $category->save();
                return $this->redirect($this->url('categories.index'));
            }
        }

        return $this->html([
            'errors' => $errors,
            'name' => $name,
            'description' => $description
        ], 'add');
    }

    public function edit(Request $request): Response
    {
        // 1. OCHRANA: Iba admin
        $user = $this->app->getAppUser();
        if (!$user || !$user->isLoggedIn() || $user->getRole() !== 'admin') {
            return $this->redirect($this->url('categories.index'));
        }

        $id = (int)$request->value('id');
        $category = Category::getOne($id);

        if (!$category) {
            return $this->redirect($this->url('categories.index'));
        }

        $errors = [];
        // Predvyplnenie dát z databázy (ak nie je POST) alebo z formulára (ak je POST a chyba)
        // Toto zabezpečí, že premenné $name a $description sú vždy definované pre View
        $name = $category->getName();
        $description = $category->getDescription();

        if ($request->isPost()) {
            $name = trim($request->post('name') ?? '');
            $description = trim($request->post('description') ?? '');

            // --- VALIDÁCIA ---
            if (mb_strlen($name) < 3) {
                $errors[] = "Názov musí mať aspoň 3 znaky";
            }
            if (mb_strlen($name) > 50) {
                $errors[] = "Názov je príliš dlhý (max 50 znakov)";
            }
            if (mb_strlen($description) > 255) {
                $errors[] = "Popis je príliš dlhý (max 255 znakov)";
            }

            if (empty($errors)) {
                $category->setName($name);
                $category->setDescription($description);
                $category->save();
                return $this->redirect($this->url('categories.index'));
            }
        }

        // Posielame dáta do View
        return $this->html([
            'category' => $category,
            'errors' => $errors,
            'name' => $name,             // Dôležité pre zachovanie hodnoty pri chybe
            'description' => $description // Dôležité pre zachovanie hodnoty pri chybe
        ], 'edit');
    }

    public function delete(Request $request): Response
    {
        // 1. OCHRANA: Iba admin
        $user = $this->app->getAppUser();
        if (!$user || !$user->isLoggedIn() || $user->getRole() !== 'admin') {
            return $this->redirect($this->url('categories.index'));
        }

        $id = (int)$request->value('id');
        $category = Category::getOne($id);

        if ($category) {
            try {
                // --- KASKÁDOVÉ MAZANIE ---

                // 1. Nájdi všetky články v tejto kategórii
                $posts = \App\Models\Post::getAll("category_id = ?", [$id]);

                foreach ($posts as $post) {
                    // 2. Pre každý článok najprv zmaž jeho komentáre
                    $comments = \App\Models\Comment::getAll("post_id = ?", [$post->getId()]);
                    foreach ($comments as $comment) {
                        $comment->delete();
                    }

                    // 3. Zmaž článok
                    $post->delete();
                }

                // 4. Nakoniec zmaž prázdnu kategóriu
                $category->delete();

            } catch (\Throwable $e) {
                // Nikdy nepoužívaj die() v produkčnom kóde, radšej redirect
                // (V reálnej appke by si tu zalogoval chybu alebo poslal FlashMessage)
                return $this->redirect($this->url('categories.index'));
            }
        }

        return $this->redirect($this->url('categories.index'));
    }
}