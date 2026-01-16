<?php

namespace App\Models;

use Framework\Core\Model;

class Comment extends Model
{
    protected ?int $id = null;
    protected int $post_id;
    protected int $user_id;
    protected string $content;
    protected ?string $created_at = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPostId(): int
    {
        return $this->post_id;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getCreatedAt(): ?string
    {
        return $this->created_at;
    }

    public function setPostId(int $post_id): void
    {
        $this->post_id = $post_id;
    }

    public function setUserId(int $user_id): void
    {
        $this->user_id = $user_id;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function setCreatedAt(string $date): void
    {
        $this->created_at = $date;
    }

    // Helper pre autora komentára
    public function getAuthor(): ?User
    {
        return User::getOne($this->user_id);
    }
}