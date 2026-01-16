<?php

namespace App\Controllers;

use Framework\Core\BaseController;
use Framework\Http\Request;
use Framework\Http\Responses\Response;
use Framework\Http\Responses\JsonResponse;
use App\Models\Comment;

class CommentsController extends BaseController
{
    /**
     * Povinná metóda z BaseController.
     */
    public function index(Request $request): Response
    {
        return $this->redirect($this->url('home.index'));
    }

    /**
     * AJAX metóda na pridanie komentára
     */
    public function add(Request $request): Response
    {
        // 1. Overenie prihlásenia - OPRAVA: getAuth() neexistuje, používame getAppUser()
        $user = $this->app->getAppUser();

        // Musíme overiť nielen či objekt existuje, ale či je aj prihlásený
        if (!$user || !$user->isLoggedIn()) {
            return new JsonResponse(['status' => 'error', 'message' => 'Musíte byť prihlásený'], 401);
        }

        // 2. Získanie dát
        $postId = $request->post('post_id');
        $content = trim($request->post('content') ?? '');

        // 3. Validácia
        if (empty($content) || strlen($content) < 3) {
            return new JsonResponse(['status' => 'error', 'message' => 'Komentár je príliš krátky'], 400);
        }

        try {
            // 4. Uloženie do DB
            $comment = new Comment();
            $comment->setPostId((int)$postId);

            // OPRAVA: User ID získame z prihláseného používateľa
            $comment->setUserId($user->getId());
            $comment->setContent($content);

            // Nastavenie dátumu
            $now = date('Y-m-d H:i:s');
            $comment->setCreatedAt($now);

            $comment->save();

            // Pripravíme dáta pre JavaScript
            $responseData = [
                'status' => 'success',
                'id' => $comment->getId(),
                'username' => $user->getUsername(), // Toto funguje, lebo AppUser deleguje metódy na User model
                'content' => htmlspecialchars($content),
                'created_at' => date("d.m.Y H:i")
            ];

            return new JsonResponse($responseData);

        } catch (\Throwable $e) {
            // Používame \Throwable namiesto \Exception, aby sme zachytili AJ chyby v kóde (napr. preklepy)
            // Toto ti vypíše presnú chybu do Network tabu
            return new JsonResponse(['status' => 'error', 'message' => 'Chyba servera: ' . $e->getMessage()], 500);
        }
    }

    /**
     * AJAX metóda na zmazanie komentára
     */
    public function delete(Request $request): Response
    {
        // 1. Overenie prihlásenia
        $user = $this->app->getAppUser();
        if (!$user || !$user->isLoggedIn()) {
            return new JsonResponse(['status' => 'error', 'message' => 'Musíte byť prihlásený'], 401);
        }

        $commentId = (int)$request->post('id');
        $comment = Comment::getOne($commentId);

        if (!$comment) {
            return new JsonResponse(['status' => 'error', 'message' => 'Komentár neexistuje'], 404);
        }

        // 2. Overenie práv (zmazať môže autor komentára alebo admin)
        // Predpokladáme, že admin má rolu 'admin'
        if ($comment->getUserId() !== $user->getId() && $user->getRole() !== 'admin') {
            return new JsonResponse(['status' => 'error', 'message' => 'Nemáte právo zmazať tento komentár'], 403);
        }

        try {
            // 3. Zmazanie
            $comment->delete();
            return new JsonResponse(['status' => 'success']);
        } catch (\Throwable $e) {
            return new JsonResponse(['status' => 'error', 'message' => 'Chyba databázy: ' . $e->getMessage()], 500);
        }
    }
}