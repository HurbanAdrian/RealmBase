<?php

namespace App\Models;

use App\Core\Model;

class Post extends Model
{
    protected ?int $id = null;
    protected int $user_id;
    protected int $category_id;
    protected string $title;
    protected string $content;
    protected string $created_at;
}
