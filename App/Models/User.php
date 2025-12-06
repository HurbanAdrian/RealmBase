<?php

namespace App\Models;

use Framework\Core\Model;
use Framework\Core\IIdentity;

class User extends Model implements IIdentity
{
    protected static ?string $tableName = 'users';

    protected ?int $id = null;
    protected string $username;
    protected string $email;
    protected string $password;
    protected string $role;

    public function getId()
    {
        return $this->id;
    }
    public function getName(): string
    {
        return $this->username ?? '';
    }
    public function getEmail()
    {
        return $this->email;
    }
    public function getRole()
    {
        return $this->role;
    }
    public function getPassword(): string
    {
        return $this->password;
    }
}
