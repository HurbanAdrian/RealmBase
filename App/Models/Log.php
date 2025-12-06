<?php

namespace App\Models;

use App\Core\Model;

class Log extends Model
{
    protected ?int $id = null;
    protected int $user_id;
    protected string $action;
    protected string $timestamp;
}
