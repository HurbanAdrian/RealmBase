<?php

namespace App\Controllers;

use App\Core\AControllerBase;
use App\Core\Responses\Response;
use App\Models\Category;

class CategoriesController extends AControllerBase
{
    public function index(): Response
    {
        $categories = Category::getAll();
        return $this->html(['categories' => $categories]);
    }

    public function add(): Response
    {
        if ($this->request()->isPost()) {
            $category = new Category();
            $category->setName($this->request()->getValue('name'));
            $category->setDescription($this->request()->getValue('description'));
            $category->save();

            return $this->redirect($this->url('categories.index'));
        }

        return $this->html();
    }

    public function edit(): Response
    {
        $id = $this->request()->getValue('id');
        $category = Category::getOne($id);

        if (!$category) {
            throw new \App\Core\Http\Exceptions\HTTPException(404);
        }

        if ($this->request()->isPost()) {
            $category->setName($this->request()->getValue('name'));
            $category->setDescription($this->request()->getValue('description'));
            $category->save();

            return $this->redirect($this->url('categories.index'));
        }

        return $this->html(['category' => $category]);
    }

    public function delete(): Response
    {
        $id = $this->request()->getValue('id');
        $category = Category::getOne($id);

        if ($category) {
            $category->delete();
        }

        return $this->redirect($this->url('categories.index'));
    }
}
