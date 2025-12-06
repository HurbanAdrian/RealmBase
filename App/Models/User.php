<?php

namespace App\Models;

use Framework\Core\Model;

class User extends Model
{
    protected ?int $id = null;
    protected string $username;
    protected string $email;
    protected string $password;
    protected string $role;

    public function getId()
    {
        return $this->id; 
    }
    public function getUsername()
    {
        return $this->username; 
    }
    public function getEmail()
    {
        return $this->email; 
    }
    public function getRole()
    {
        return $this->role; 
    }
}
