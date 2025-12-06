<?php

namespace App\Models;

use Framework\Core\Model;

class Category extends Model
{
    protected ?int $id = null;
    protected string $name;
    protected ?string $description;

    public function getId(): ?int
    {
        return $this->id; 
    }
    public function getName(): string
    {
        return $this->name; 
    }
    public function getDescription(): ?string
    {
        return $this->description; 
    }

    public function setName(string $name)
    {
        $this->name = $name; 
    }
    public function setDescription(?string $desc)
    {
        $this->description = $desc; 
    }
}
