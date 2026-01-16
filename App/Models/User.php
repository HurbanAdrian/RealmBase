<?php

namespace App\Models;

use Framework\Core\Model;
use Framework\Core\IIdentity;

class User extends Model implements IIdentity
{
    protected ?int $id = null;
    protected string $username;
    protected ?string $email;
    protected string $password;
    protected string $role = 'user';

    // --- GETTERY ---

    public function getId(): ?int
    {
        return $this->id;
    }

    // Toto vyžaduje framework (IIdentity) pre prihláseného usera
    public function getName(): string
    {
        return $this->username;
    }

    // Toto sme použili v zozname článkov (môžeš používať aj getName, je to jedno)
    public function getUsername(): string
    {
        return $this->username;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    // TOTO TI CHÝBALO - prečítanie hesla pri logine
    public function getPassword(): string
    {
        return $this->password;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    // --- SETTERY ---

    public function setUsername(string $username)
    {
        $this->username = $username;
    }

    public function setEmail(?string $email)
    {
        $this->email = $email;
    }

    public function setPassword(string $password)
    {
        $this->password = $password;
    }

    public function setRole(string $role)
    {
        $this->role = $role;
    }
}