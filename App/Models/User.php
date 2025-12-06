<?php

namespace App\Models;

use App\Core\Model;

class User extends Model
{
    protected ?int $id = null;
    protected string $username;
    protected string $email;
    protected string $password;
    protected string $role;

    public function getId(): ?int { return $this->id; }
    public function getUsername(): string { return $this->username; }
    public function getEmail(): string { return $this->email; }
    public function getRole(): string { return $this->role; }

    public function setUsername(string $u) { $this->username = $u; }
    public function setEmail(string $e) { $this->email = $e; }
    public function setPassword(string $p) { $this->password = $p; }
    public function setRole(string $r) { $this->role = $r; }
}
