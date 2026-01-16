<?php

namespace App\Models;

use Framework\Core\Model;

class Post extends Model
{
    protected ?int $id = null;
    protected int $user_id;
    protected int $category_id;
    protected string $title;
    protected string $content;
    protected ?string $created_at = null;
    protected ?string $image = null;

    public function getId()
    {
        return $this->id;
    }
    public function getUserId()
    {
        return $this->user_id;
    }
    public function getCategoryId()
    {
        return $this->category_id;
    }
    public function getTitle()
    {
        return $this->title;
    }
    public function getContent()
    {
        return $this->content;
    }
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function setUserId(int $u)
    {
        $this->user_id = $u;
    }
    public function setCategoryId(int $c)
    {
        $this->category_id = $c;
    }
    public function setTitle(string $t)
    {
        $this->title = $t;
    }
    public function setContent(string $c)
    {
        $this->content = $c;
    }

    /**
     * Helper metóda na získanie objektu autora článku
     */
    public function getAuthor(): ?User
    {
        return User::getOne($this->user_id);
    }

    /**
     * Helper metóda na získanie objektu kategórie
     */
    public function getCategory(): ?Category
    {
        return Category::getOne($this->category_id);
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): void
    {
        $this->image = $image;
    }
}