<?php

namespace App\Models;

use Framework\Core\Model;

class Comment extends Model
{
    protected ?int $id = null;
    protected int $post_id;
    protected int $user_id;
    protected string $content;
    protected string $created_at;
}

