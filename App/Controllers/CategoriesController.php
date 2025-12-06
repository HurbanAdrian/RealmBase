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
        $errors = [];
        $name = trim($request->post('name') ?? '');
        $description = trim($request->post('description') ?? '');

        if ($request->isPost()) {
            // VALIDÁCIA NAZVU
            if (strlen($name) < 3) {
                $errors[] = "Názov musí mať aspoň 3 znaky.";
            }

            if (strlen($name) > 50) {
                $errors[] = "Názov kategórie môže mať maximálne 50 znakov.";
            }

            // UNIKÁTNOSŤ
            $existing = Category::getAll();
            foreach ($existing as $cat) {
                if (strtolower($cat->getName()) === strtolower($name)) {
                    $errors[] = "Kategória s týmto názvom už existuje.";
                    break;
                }
            }

            if (empty($errors)) {
                $category = new Category();
                $category->setName($name);
                $category->setDescription($description);
                $category->save();

                return $this->redirect($this->url('categories.index'));
            }
        }

        return $this->html(
            [
            'errors' => $errors,
            'name' => $name,
            'description' => $description
            ],
            'add'
        );
    }



    public function edit(Request $request): Response
    {
        $id = $request->value('id');
        $category = Category::getOne($id);

        if (!$category) {
            return $this->redirect($this->url('categories.index'));
        }

        $errors = [];
        $name = trim($request->post('name') ?? '');
        $description = trim($request->post('description') ?? '');


        if ($request->isPost()) {
            // VALIDÁCIA NAZVU
            if (strlen($name) < 3) {
                $errors[] = "Názov musí mať aspoň 3 znaky.";
            }

            if (strlen($name) > 50) {
                $errors[] = "Názov kategórie môže mať maximálne 50 znakov.";
            }

            // UNIQUE CHECK → OKREM tejto kategórie
            $existing = Category::getAll();
            foreach ($existing as $cat) {
                if (
                    strtolower($cat->getName()) === strtolower($name)
                    && $cat->getId() !== $category->getId()
                ) {
                    $errors[] = "Kategória s týmto názvom už existuje.";
                    break;
                }
            }

            if (empty($errors)) {
                $category->setName($name);
                $category->setDescription($description);
                $category->save();

                return $this->redirect($this->url('categories.index'));
            }
        }

        return $this->html(
            [
            'errors' => $errors,
            'name' => $name,
            'description' => $description,
            'category' => $category
            ],
            'edit'
        );
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
