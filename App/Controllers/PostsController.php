<?php

namespace App\Controllers;

use Framework\Core\BaseController;
use Framework\Http\Request;
use Framework\Http\Responses\Response;
use App\Models\Post;
use App\Models\Category;
use App\Models\User;

class PostsController extends BaseController
{
    public function index(Request $request): Response
    {
        $posts = Post::getAll();
        return $this->html(['posts' => $posts], 'index');
    }

    public function add(Request $request): Response
    {
        $errors = [];

        $title = trim($request->post('title') ?? '');
        $content = trim($request->post('content') ?? '');
        $categoryId = $request->post('category_id') ?? null;
        $userId = $request->post('user_id') ?? null;

        $categories = Category::getAll();
        $users = User::getAll();

        if ($request->isPost()) {

            // VALIDÁCIA TITLE
            if (strlen($title) < 3) {
                $errors[] = "Nadpis musí mať aspoň 3 znaky.";
            }

            if (strlen($title) > 200) {
                $errors[] = "Nadpis môže mať najviac 200 znakov.";
            }

            // VALIDÁCIA CONTENT
            if (strlen($content) < 10) {
                $errors[] = "Obsah musí mať aspoň 10 znakov.";
            }

            // VALIDÁCIA CATEGORY
            if (!$categoryId || !Category::getOne($categoryId)) {
                $errors[] = "Musíte vybrať platnú kategóriu.";
            }

            // VALIDÁCIA USER
            if (!$userId || !User::getOne($userId)) {
                $errors[] = "Musíte vybrať autora.";
            }

            if (empty($errors)) {
                $post = new Post();
                $post->setTitle($title);
                $post->setContent($content);
                $post->setCategoryId((int)$categoryId);
                $post->setUserId((int)$userId);
                $post->save();

                return $this->redirect($this->url('posts.index'));
            }
        }

        return $this->html(
            [
            'errors' => $errors,
            'title' => $title,
            'content' => $content,
            'categories' => $categories,
            'users' => $users,
            'category_id' => $categoryId,
            'user_id' => $userId
            ], 'add'
        );
    }


    public function edit(Request $request): Response
    {
        $id = $request->value('id');
        $post = Post::getOne($id);

        if (!$post) {
            return $this->redirect($this->url('posts.index'));
        }

        $errors = [];

        $title = trim($request->post('title') ?? $post->getTitle());
        $content = trim($request->post('content') ?? $post->getContent());
        $categoryId = $request->post('category_id') ?? $post->getCategoryId();
        $userId = $request->post('user_id') ?? $post->getUserId();

        $categories = Category::getAll();
        $users = User::getAll();

        if ($request->isPost()) {

            if (strlen($title) < 3) {
                $errors[] = "Nadpis musí mať aspoň 3 znaky.";
            }

            if (strlen($title) > 200) {
                $errors[] = "Nadpis môže mať najviac 200 znakov.";
            }

            if (strlen($content) < 10) {
                $errors[] = "Obsah musí mať aspoň 10 znakov.";
            }

            if (!$categoryId || !Category::getOne($categoryId)) {
                $errors[] = "Musíte vybrať platnú kategóriu.";
            }

            if (!$userId || !User::getOne($userId)) {
                $errors[] = "Musíte vybrať autora.";
            }

            if (empty($errors)) {
                $post->setTitle($title);
                $post->setContent($content);
                $post->setCategoryId((int)$categoryId);
                $post->setUserId((int)$userId);
                $post->save();

                return $this->redirect($this->url('posts.index'));
            }
        }

        return $this->html(
            [
            'errors' => $errors,
            'post' => $post,
            'title' => $title,
            'content' => $content,
            'category_id' => $categoryId,
            'user_id' => $userId,
            'categories' => $categories,
            'users' => $users
            ], 'edit'
        );
    }



    public function delete(Request $request): Response
    {
        $id = $request->value('id');
        $post = Post::getOne($id);

        if ($post) {
            $post->delete();
        }

        return $this->redirect($this->url('posts.index'));
    }
}
