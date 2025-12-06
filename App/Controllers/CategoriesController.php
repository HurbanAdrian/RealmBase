<?php

namespace App\Controllers;

use Framework\Core\BaseController;
use Framework\Http\Request;
use Framework\Http\Responses\Response;
use App\Models\Category;

class CategoriesController extends BaseController
{
    public function index(Request $request): Response
    {
        $categories = Category::getAll();
        return $this->html(['categories' => $categories], 'index');
    }

    public function add(Request $request): Response
    {
        if ($request->isPost()) {
            $category = new Category();

            $category->setName($request->post('name'));
            $category->setDescription($request->post('description'));

            $category->save();

            return $this->redirect($this->url('categories.index'));
        }

        return $this->html([], 'add');
    }

    public function edit(Request $request): Response
    {
        $id = $request->value('id');
        $category = Category::getOne($id);

        if (!$category) {
            return $this->redirect($this->url('categories.index'));
        }

        if ($request->isPost()) {
            $category->setName($request->post('name'));
            $category->setDescription($request->post('description'));
            $category->save();

            return $this->redirect($this->url('categories.index'));
        }

        return $this->html(['category' => $category], 'edit');
    }

    public function delete(Request $request): Response
    {
        $id = $request->value('id');
        $category = Category::getOne($id);

        if ($category) {
            $category->delete();
        }

        return $this->redirect($this->url('categories.index'));
    }
}
