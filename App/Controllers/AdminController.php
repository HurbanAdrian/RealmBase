<?php

namespace App\Controllers;

use Framework\Core\BaseController;
use Framework\Http\Request;
use Framework\Http\Responses\Response;

// Implementované s asistenciou AI

/**
 * Class AdminController
 * Manages admin-related actions: listing and deleting users.
 */
class AdminController extends BaseController
{
    /**
     * Overenie práv.
     */
    public function authorize(Request $request, string $action): bool
    {
        $user = $this->app->getAppUser();
        // Overíme, či user existuje, je prihlásený a je admin
        if ($user && $user->isLoggedIn() && $user->getRole() === 'admin') {
            return true;
        }
        return false;
    }

    /**
     * Dashboard - zoznam používateľov
     */
    public function index(Request $request): Response
    {
        $users = \App\Models\User::getAll();
        return $this->html(['users' => $users], 'index');
    }

    /**
     * Zmaže používateľa a VŠETKY jeho dáta
     */
    public function deleteUser(Request $request): Response
    {
        $id = (int)$request->value('id');
        $currentUser = $this->app->getAppUser();

        // Admin nemôže zmazať sám seba!
        if ($id === $currentUser->getId()) {
            return $this->redirect($this->url('admin.index'));
        }

        $userToDelete = \App\Models\User::getOne($id);

        if ($userToDelete) {
            try {
                // 1. Zmažeme všetky KOMENTÁRE tohto používateľa
                $userComments = \App\Models\Comment::getAll("user_id = ?", [$id]);
                foreach ($userComments as $comment) {
                    $comment->delete();
                }

                // 2. Zmažeme všetky ČLÁNKY tohto používateľa
                $userPosts = \App\Models\Post::getAll("user_id = ?", [$id]);
                foreach ($userPosts as $post) {
                    // 1. Zmažeme fotku z disku
                    if ($post->getImage()) {
                        $imagePath = 'public/uploads/' . $post->getImage();
                        if (file_exists($imagePath)) {
                            unlink($imagePath);
                        }
                    }
                    // Potom zmažeme komentáre k článku
                    $postComments = \App\Models\Comment::getAll("post_id = ?", [$post->getId()]);
                    foreach ($postComments as $pc) {
                        $pc->delete();
                    }
                    $post->delete();
                }

                // 3. Konečne zmažeme USERA
                $userToDelete->delete();

            } catch (\Throwable $e) {
                // V prípade chyby len presmerujeme späť, nezobrazíme bielu obrazovku s chybou
                // (V reálnej appke by sa chyba zapísala do logu)
                return $this->redirect($this->url('admin.index'));
            }
        }

        return $this->redirect($this->url('admin.index'));
    }
}