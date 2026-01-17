<?php

namespace App\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use Framework\Core\BaseController;
use Framework\Http\Request;
use Framework\Http\Responses\Response;

// Implementované s asistenciou AI

class PostsController extends BaseController
{
    /**
     * Zobrazí zoznam článkov
     */
    public function index(Request $request): Response
    {
        // 1. Získanie parametrov z URL
        // Skúsime získať kategóriu z rôznych zdrojov, ktoré framework používa
        $categoryId = (int) ($request->value('category') ?? $_GET['category'] ?? 0);
        $sortBy = $request->value('sort') ?? 'created_at'; // Predvolené: dátum
        $order = strtoupper($request->value('order') ?? 'DESC');      // Predvolené: najnovšie && Prevod na veľké písmená

        // 2. Bezpečnostná kontrola (Whitelist), aby niekto nepodstrčil zlý SQL príkaz
        $allowedFields = ['id', 'title', 'category_id', 'created_at'];
        if (!in_array($sortBy, $allowedFields)) {
            $sortBy = 'created_at';
        }

        // Povolené len ASC (A-Z / Najstaršie) alebo DESC (Z-A / Najnovšie)
        if (!in_array($order, ['ASC', 'DESC'])) {
            $order = 'DESC';
        }

        // 3. Logika výberu dát
        $orderByString = "$sortBy $order";

        if ($categoryId > 0) {
            // getAll(podmienka, parametre, zoradenie)
            $posts = Post::getAll("category_id = ?", [$categoryId], $orderByString);
        } else {
            // getAll(null, prázdne pole, zoradenie)
            $posts = Post::getAll(null, [], $orderByString);
        }

        return $this->html([
            'posts' => $posts,
            'currentSort' => $sortBy,
            'currentOrder' => $order,
            'currentCategory' => $categoryId
        ], 'index');
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

                // SPRACUJEME OBRÁZOK
                $imageName = $this->handleImageUpload($request);
                if ($imageName) {
                    $post->setImage($imageName);
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

                // --- OBRÁZOK PRI EDITE ---
                $newImage = $this->handleImageUpload($request);
                if ($newImage) {
                    // Ak už mal starý obrázok, zmažeme ho z disku (upratovanie)
                    if ($post->getImage() && file_exists('public/uploads/' . $post->getImage())) {
                        unlink('public/uploads/' . $post->getImage());
                    }
                    $post->setImage($newImage);
                }

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


    private function handleImageUpload(Request $request): ?string
    {
        // Skontrolujeme, či bol súbor vôbec odoslaný a či nie je chyba
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {

            // --- PRIDANÁ KONTROLA VEĽKOSTI (2MB = 2 * 1024 * 1024 bajtov) ---
            if ($_FILES['image']['size'] > 2097152) {
                return null; // Súbor je príliš veľký, ignorujeme ho
            }

            $uploadDir = 'public/uploads/';

            // Ak priečinok neexistuje, vytvoríme ho
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            // Získame príponu a vytvoríme unikátny názov (ochrana pred prepísaním)
            $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $fileName = time() . '_' . uniqid() . '.' . $extension;
            $targetFile = $uploadDir . $fileName;

            // Validácia typu (iba obrázky)
            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
            if (in_array(strtolower($extension), $allowedTypes)) {
                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                    return $fileName; // Vrátime názov súboru, ktorý uložíme do DB
                }
            }
        }
        return null;
    }
}