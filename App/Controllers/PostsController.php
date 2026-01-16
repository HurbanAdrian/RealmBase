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
     * Zobrazí zoznam článkov (všetky alebo filtrované podľa kategórie)
     */
    public function index(Request $request): Response
    {
        // 1. Zistíme, či chceme filtrovať podľa kategórie (?category=5)
        $categoryId = (int)$request->value('category');

        if ($categoryId > 0) {
            // Načítame len články z danej kategórie
            $posts = Post::getAll("category_id = ?", [$categoryId]);
        } else {
            // Načítame všetky
            $posts = Post::getAll();
        }

        return $this->html(['posts' => $posts], 'index');
    }

    public function show(Request $request): Response
    {
        $id = $request->value('id');
        $post = Post::getOne($id);

        if (!$post) {
            return $this->redirect($this->url('posts.index'));
        }

        // Načítame komentáre k článku
        $comments = \App\Models\Comment::getAll("post_id = ?", [$id]);

        return $this->html([
            'post' => $post,
            'comments' => $comments
        ], 'show');
    }

    public function add(Request $request): Response
    {
        $user = $this->app->getAppUser();
        if (!$user || !$user->isLoggedIn()) {
            return $this->redirect($this->url('auth.login'));
        }

        $categories = Category::getAll();
        // Pre výber autora (len pre admina, inak sa nastaví aktuálny user)
        $users = User::getAll();

        $title = $request->post('title');
        $content = $request->post('content');
        $categoryId = $request->post('category_id');
        $userId = $request->post('user_id');

        $errors = [];

        if ($request->isPost()) {
            if (strlen($title) < 3) $errors[] = "Nadpis je príliš krátky";
            if (strlen($content) < 10) $errors[] = "Obsah je príliš krátky";
            if (empty($categoryId)) $errors[] = "Vyberte kategóriu";

            if (empty($errors)) {
                $post = new Post();
                $post->setTitle($title);
                $post->setContent($content);
                $post->setCategoryId((int)$categoryId);

                // Ak je admin a vybral iného autora, použijeme to. Inak aktuálny user.
                if ($user->getRole() === 'admin' && !empty($userId)) {
                    $post->setUserId((int)$userId);
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
            // Nemáš právo -> presmerujeme
            return $this->redirect($this->url('posts.index'));
        }

        $categories = Category::getAll();
        $users = User::getAll();
        $errors = [];

        if ($request->isPost()) {
            $title = $request->post('title');
            $content = $request->post('content');
            $categoryId = $request->post('category_id');

            if (strlen($title) < 3) $errors[] = "Nadpis je príliš krátky";
            if (strlen($content) < 10) $errors[] = "Obsah je príliš krátky";

            if (empty($errors)) {
                $post->setTitle($title);
                $post->setContent($content);
                $post->setCategoryId((int)$categoryId);
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

    public function delete(Request $request): Response
    {
        $id = (int)$request->value('id');
        $post = Post::getOne($id);

        if ($post) {
            $user = $this->app->getAppUser();

            if ($user && $user->isLoggedIn()) {
                if ($post->getUserId() === $user->getId() || $user->getRole() === 'admin') {

                    try {
                        // 1. Najprv zmažeme všetky komentáre k tomuto článku
                        $comments = \App\Models\Comment::getAll("post_id = ?", [$post->getId()]);
                        foreach ($comments as $comment) {
                            $comment->delete();
                        }

                        // 2. Potom zmažeme samotný článok
                        $post->delete();

                    } catch (\Throwable $e) {
                        // Ak by nastala chyba, vypíšeme ju (alebo len logujeme)
                        die("Nepodarilo sa zmazať článok: " . $e->getMessage());
                    }

                }
            }
        }

        return $this->redirect($this->url('posts.index'));
    }
}