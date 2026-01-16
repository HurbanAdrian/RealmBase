<?php

namespace App\Controllers;

use App\Models\Category;
use Framework\Core\BaseController;
use Framework\Http\Request;
use Framework\Http\Responses\Response;

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

        $name = $request->post('name');
        $description = $request->post('description');
        $errors = [];

        if ($request->isPost()) {
            if (strlen($name) < 3) {
                $errors[] = "Názov musí mať aspoň 3 znaky";
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
        if ($request->isPost()) {
            $name = $request->post('name');
            $description = $request->post('description');

            if (strlen($name) < 3) {
                $errors[] = "Názov musí mať aspoň 3 znaky";
            }

            if (empty($errors)) {
                $category->setName($name);
                $category->setDescription($description);
                $category->save();
                return $this->redirect($this->url('categories.index'));
            }
        }

        return $this->html(['category' => $category, 'errors' => $errors], 'edit');
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
                die("Chyba pri mazaní kategórie: " . $e->getMessage());
            }
        }

        return $this->redirect($this->url('categories.index'));
    }
}