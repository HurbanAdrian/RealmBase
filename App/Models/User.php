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
}
