<?php

namespace App\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use Framework\Core\BaseController;
use Framework\Http\Request;
use Framework\Http\Responses\Response;

class PostsController extends BaseController
{
    /**
     * Zobrazí zoznam článkov
     */
    public function index(Request $request): Response
    {
        $categoryId = (int)$request->value('category');

        if ($categoryId > 0) {
            $posts = Post::getAll("category_id = ?", [$categoryId]);
        } else {
            $posts = Post::getAll();
        }

        return $this->html(['posts' => $posts], 'index');
    }

    /**
     * Zobrazí detail článku
     */
    public function show(Request $request): Response
    {
        $id = (int)$request->value('id'); // Pretypovanie na int pre istotu
        $post = Post::getOne($id);

        if (!$post) {
            return $this->redirect($this->url('posts.index'));
        }

        $comments = \App\Models\Comment::getAll("post_id = ?", [$id]);

        return $this->html([
            'post' => $post,
            'comments' => $comments
        ], 'show');
    }

    /**
     * Pridanie článku
     */
    public function add(Request $request): Response
    {
        $user = $this->app->getAppUser();
        if (!$user || !$user->isLoggedIn()) {
            return $this->redirect($this->url('auth.login'));
        }

        $categories = Category::getAll();
        $users = User::getAll();

        // Získame dáta a ošetríme medzery
        $title = trim($request->post('title') ?? '');
        $content = trim($request->post('content') ?? '');
        $categoryId = (int)$request->post('category_id');
        $userId = (int)$request->post('user_id');

        $errors = [];

        if ($request->isPost()) {
            // --- VALIDÁCIA ---
            // 1. Min. dĺžka
            if (mb_strlen($title) < 3) $errors[] = "Nadpis je príliš krátky (min. 3 znaky).";
            if (mb_strlen($content) < 10) $errors[] = "Obsah je príliš krátky (min. 10 znakov).";

            // 2. Max. dĺžka (Ochrana databázy)
            if (mb_strlen($title) > 255) $errors[] = "Nadpis je príliš dlhý (max. 255 znakov).";
            // Obsah v DB je asi TEXT (65k), ale dajme rozumný limit pre web
            if (mb_strlen($content) > 20000) $errors[] = "Obsah je príliš dlhý.";

            // 3. Povinné polia
            if ($categoryId <= 0) $errors[] = "Vyberte kategóriu.";

            if (empty($errors)) {
                $post = new Post();
                $post->setTitle($title);
                $post->setContent($content);
                $post->setCategoryId($categoryId);

                // Admin môže nastaviť autora, inak je to aktuálny user
                if ($user->getRole() === 'admin' && $userId > 0) {
                    $post->setUserId($userId);
                } else {
                    $post->setUserId($user->getId());
                }

                $post->save();
                return $this->redirect($this->url('posts.index'));
            }
        }

        return $this->html([
            'errors' => $errors,
            'categories' => $categories,
            'users' => $users,
            'title' => $title,
            'content' => $content,
            'category_id' => $categoryId,
            'user_id' => $userId
        ], 'add');
    }

    /**
     * Editácia článku
     */
    public function edit(Request $request): Response
    {
        $id = (int)$request->value('id');
        $post = Post::getOne($id);

        if (!$post) {
            return $this->redirect($this->url('posts.index'));
        }

        // OCHRANA: Editovať môže len autor alebo Admin
        $user = $this->app->getAppUser();
        if (!$user || !$user->isLoggedIn()) {
            return $this->redirect($this->url('auth.login'));
        }

        if ($post->getUserId() !== $user->getId() && $user->getRole() !== 'admin') {
            return $this->redirect($this->url('posts.index'));
        }

        $categories = Category::getAll();
        $users = User::getAll();
        $errors = [];

        if ($request->isPost()) {
            $title = trim($request->post('title') ?? '');
            $content = trim($request->post('content') ?? '');
            $categoryId = (int)$request->post('category_id');

            // --- VALIDÁCIA ---
            if (mb_strlen($title) < 3) $errors[] = "Nadpis je príliš krátky (min. 3 znaky).";
            if (mb_strlen($content) < 10) $errors[] = "Obsah je príliš krátky (min. 10 znakov).";

            // Ochrana DB
            if (mb_strlen($title) > 255) $errors[] = "Nadpis je príliš dlhý (max. 255 znakov).";
            if (mb_strlen($content) > 20000) $errors[] = "Obsah je príliš dlhý.";

            if ($categoryId <= 0) $errors[] = "Vyberte kategóriu.";

            if (empty($errors)) {
                $post->setTitle($title);
                $post->setContent($content);
                $post->setCategoryId($categoryId);
                $post->save();

                return $this->redirect($this->url('posts.index'));
            }
        }

        return $this->html([
            'post' => $post,
            'errors' => $errors,
            'categories' => $categories,
            'users' => $users
        ], 'edit');
    }

    /**
     * Zmazanie článku
     */
    public function delete(Request $request): Response
    {
        $id = (int)$request->value('id');
        $post = Post::getOne($id);

        if ($post) {
            $user = $this->app->getAppUser();

            if ($user && $user->isLoggedIn()) {
                if ($post->getUserId() === $user->getId() || $user->getRole() === 'admin') {

                    try {
                        // 1. Zmažeme komentáre
                        $comments = \App\Models\Comment::getAll("post_id = ?", [$post->getId()]);
                        foreach ($comments as $comment) {
                            $comment->delete();
                        }

                        // 2. Zmažeme článok
                        $post->delete();

                    } catch (\Throwable $e) {
                        // V reálnej appke by sme to logovali do súboru
                        // Tu aspoň zabránime pádu a presmerujeme späť
                        // Ak by si mal FlashMessages, tu by si poslal chybu
                    }

                }
            }
        }

        return $this->redirect($this->url('posts.index'));
    }
}