<?php

namespace App\Controllers;

use Framework\Core\BaseController;
use Framework\Http\Responses\Response;
use App\Models\Category;

class CategoriesController extends BaseController
{
    public function index(): Response
    {
        $categories = Category::getAll();
        return $this->view('Categories/index', [
            'categories' => $categories
        ]);
    }

    public function add(): Response
    {
        if ($this->request()->isPost()) {
            $cat = new Category();
            $cat->setName($this->request()->getPost('name'));
            $cat->setDescription($this->request()->getPost('description'));
            $cat->save();

            return $this->redirect('/?c=categories');
        }

        return $this->view('Categories/add');
    }

    public function edit(): Response
    {
        $id = $this->request()->getValue('id');
        $category = Category::getOne($id);

        if (!$category) {
            return $this->redirect('/?c=categories');
        }

        if ($this->request()->isPost()) {
            $category->setName($this->request()->getPost('name'));
            $category->setDescription($this->request()->getPost('description'));
            $category->save();

            return $this->redirect('/?c=categories');
        }

        return $this->view('Categories/edit', [
            'category' => $category
        ]);
    }

    public function delete(): Response
    {
        $id = $this->request()->getValue('id');
        $category = Category::getOne($id);

        if ($category) {
            $category->delete();
        }

        return $this->redirect('/?c=categories');
    }
}
