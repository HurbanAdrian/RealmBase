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
        $categories = Category::getAll();
        $users = User::getAll();

        if ($request->isPost()) {
            $post = new Post();
            $post->setTitle($request->post('title'));
            $post->setContent($request->post('content'));
            $post->setCategoryId($request->post('category_id'));
            $post->setUserId($request->post('user_id'));

            $post->save();
            return $this->redirect($this->url('posts.index'));
        }

        return $this->html([
            'categories' => $categories,
            'users' => $users
        ], 'add');
    }

    public function edit(Request $request): Response
    {
        $id = $request->value('id');
        $post = Post::getOne($id);

        if (!$post) {
            return $this->redirect($this->url('posts.index'));
        }

        $categories = Category::getAll();
        $users = User::getAll();

        if ($request->isPost()) {
            $post->setTitle($request->post('title'));
            $post->setContent($request->post('content'));
            $post->setCategoryId($request->post('category_id'));
            $post->setUserId($request->post('user_id'));
            $post->save();

            return $this->redirect($this->url('posts.index'));
        }

        return $this->html([
            'post' => $post,
            'categories' => $categories,
            'users' => $users
        ], 'edit');
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
