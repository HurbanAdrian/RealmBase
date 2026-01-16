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
            // Trimujeme username (užívatelia často omylom skopírujú medzeru)
            $username = trim($request->value('username') ?? '');
            $password = $request->value('password'); // Heslo netrimujeme, medzera môže byť súčasťou hesla

            $logged = $this->app->getAuthenticator()->login($username, $password);

            if ($logged) {
                return $this->redirect($this->url("home.index"));
            }
        }

        $message = $logged === false ? 'Nesprávne používateľské meno alebo heslo' : null;
        return $this->html(compact("message"));
    }

    /**
     * Odhlásenie
     */
    public function logout(Request $request): Response
    {
        $this->app->getAuthenticator()->logout();
        return $this->redirect($this->url('home.index'));
    }

    /**
     * Registrácia nového používateľa
     */
    public function register(Request $request): Response
    {
        $data = [];
        $errors = [];

        // Zistenie, či ide o POST request
        $isPost = false;
        if (method_exists($request, 'isPost')) {
            $isPost = $request->isPost();
        } elseif (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $isPost = true;
        }

        if ($isPost) {
            // 1. Získanie a očistenie dát
            $username = trim($request->post('username') ?? '');
            $email = trim($request->post('email') ?? '');
            $password = $request->post('password') ?? ''; // Heslo netrimuj!
            $passwordVerify = $request->post('password_verify') ?? '';

            // 2. VALIDÁCIA

            // --- Username ---
            if (mb_strlen($username) < 3) {
                $errors[] = "Meno musí mať aspoň 3 znaky.";
            }
            if (mb_strlen($username) > 50) { // DB limit
                $errors[] = "Meno je príliš dlhé (max 50 znakov).";
            }

            // --- Email ---
            if (empty($email)) {
                $errors[] = "Zadajte e-mail.";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Neplatný formát e-mailu.";
            }
            if (mb_strlen($email) > 255) { // DB limit
                $errors[] = "E-mail je príliš dlhý.";
            }

            // --- Password ---
            if (mb_strlen($password) < 4) {
                $errors[] = "Heslo musí mať aspoň 4 znaky.";
            }
            if ($password !== $passwordVerify) {
                $errors[] = "Heslá sa nezhodujú.";
            }

            // --- Duplicita (Optimalizované) ---
            // Skontrolujeme, či už user existuje jedným rýchlym dotazom do DB
            // Namiesto načítania všetkých userov a cyklenia v PHP
            if (empty($errors)) {
                $existingUser = \App\Models\User::getAll("username = ?", [$username]);
                if (!empty($existingUser)) {
                    $errors[] = "Používateľ s týmto menom už existuje.";
                }

                // Bonus: Kontrola duplicity emailu
                $existingEmail = \App\Models\User::getAll("email = ?", [$email]);
                if (!empty($existingEmail)) {
                    $errors[] = "Tento e-mail je už registrovaný.";
                }
            }

            // 3. Ak je všetko OK, vytvoríme usera
            if (empty($errors)) {
                try {
                    $user = new \App\Models\User();
                    $user->setUsername($username);
                    $user->setEmail($email);

                    // Hashovanie hesla
                    $user->setPassword(password_hash($password, PASSWORD_DEFAULT));

                    $user->setRole('user');
                    $user->save();

                    // Presmerujeme na login
                    return $this->redirect($this->url('auth.login'));

                } catch (\Throwable $e) {
                    $errors[] = "Chyba pri registrácii: " . $e->getMessage();
                }
            }

            // Ak boli chyby, vrátime zadané údaje do formulára (okrem hesla!)
            $data['username'] = $username;
            $data['email'] = $email;
        }

        return $this->html([
            'errors' => $errors,
            'formData' => $data ?? []
        ], 'register');
    }
}