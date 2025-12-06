<?php

namespace App\Auth;

use App\Models\User;
use Framework\Auth\SessionAuthenticator;

class SessionAuthenticatorImpl extends SessionAuthenticator
{
    protected function authenticate(string $username, string $password): ?\Framework\Core\IIdentity
    {
        // getAll is the ONLY method that supports custom WHERE filtering
        error_log("LOGIN ATTEMPT username='$username'");

        // Test 1 — Get ALL users
        $all = User::getAll();
        error_log("ALL USERS: " . print_r($all, true));

        // Test 2 — Try exact query
        $users = User::getAll("username = ?", [$username]);
        error_log("FILTERED USERS: " . print_r($users, true));

        // No such user?
        if (empty($users)) {
            return null;
        }

        $user = $users[0];

        // Password mismatch?
        if (!password_verify($password, $user->getPassword())) {
            return null;
        }

        // Must return IIdentity -> User implements IIdentity
        return $user;
    }
}
