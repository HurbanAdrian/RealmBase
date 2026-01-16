<?php

namespace App\Controllers;

use App\Configuration;
use Exception;
use Framework\Core\BaseController;
use Framework\Http\Request;
use Framework\Http\Responses\Response;
use Framework\Http\Responses\ViewResponse;

/**
 * Class AuthController
 *
 * This controller handles authentication actions such as login, logout, and redirection to the login page. It manages
 * user sessions and interactions with the authentication system.
 *
 * @package App\Controllers
 */
class AuthController extends BaseController
{
    /**
     * Redirects to the login page.
     */
    public function index(Request $request): Response
    {
        return $this->redirect(Configuration::LOGIN_URL);
    }

    /**
     * Prihlásenie používateľa
     */
    public function login(Request $request): Response
    {
        $logged = null;
        if ($request->hasValue('submit')) {
            $logged = $this->app->getAuthenticator()->login($request->value('username'), $request->value('password'));
            if ($logged) {
                // Po úspešnom prihlásení presmerujeme.
                // Ak chceš, aby bežní useri nešli na admin stránku, môžeš to zmeniť na 'home.index'
                return $this->redirect($this->url("home.index"));
            }
        }

        $message = $logged === false ? 'Bad username or password' : null;
        return $this->html(compact("message"));
    }

    /**
     * Odhlásenie
     */
    public function logout(Request $request): Response
    {
        $this->app->getAuthenticator()->logout();
        // Po odhlásení presmerujeme na login alebo domov
        return $this->redirect($this->url('home.index'));
    }

    /**
     * Registrácia nového používateľa
     */
    public function register(Request $request): Response
    {
        $data = [];
        $errors = [];

        // OPRAVA: Používame premennú $request, ktorá prišla ako parameter, nie $this->request()
        // Poznámka: Ak tvoj framework nemá metódu isPost(), použi: $request->server('REQUEST_METHOD') === 'POST'
        // Alebo skontroluj či bol odoslaný formulár
        $isPost = false;
        if (method_exists($request, 'isPost')) {
            $isPost = $request->isPost();
        } elseif (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $isPost = true;
        }

        if ($isPost) {
            $username = $request->post('username');
            $email = $request->post('email');
            $password = $request->post('password');
            $passwordVerify = $request->post('password_verify');

            // 1. Validácia
            if (empty($username) || strlen($username) < 3) {
                $errors[] = "Meno musí mať aspoň 3 znaky.";
            }
            if (empty($password) || strlen($password) < 4) {
                $errors[] = "Heslo musí mať aspoň 4 znaky.";
            }
            if ($password !== $passwordVerify) {
                $errors[] = "Heslá sa nezhodujú.";
            }

            // Kontrola, či užívateľ s takým menom už neexistuje
            $existingUsers = \App\Models\User::getAll();
            foreach ($existingUsers as $u) {
                if ($u->getUsername() === $username) {
                    $errors[] = "Používateľ s týmto menom už existuje.";
                    break;
                }
            }

            // 2. Ak je všetko OK, vytvoríme usera
            if (empty($errors)) {
                $user = new \App\Models\User();
                $user->setUsername($username);
                $user->setEmail($email);

                // Heslo zahashujeme
                $user->setPassword(password_hash($password, PASSWORD_DEFAULT));

                $user->setRole('user'); // Bežný user
                $user->save();

                // Presmerujeme na login
                return $this->redirect($this->url('auth.login'));
            }

            // Ak boli chyby, vrátime ich do formulára
            $data['username'] = $username;
            $data['email'] = $email;
        }

        return $this->html([
            'errors' => $errors,
            'formData' => $data ?? []
        ], 'register');
    }
}