<?php

namespace App\Models;

use App\Core\Model;

class Category extends Model
{
    protected ?int $id = null;
    protected string $name;
    protected ?string $description;

    public function getId(): ?int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getDescription(): ?string { return $this->description; }

    public function setName(string $n) { $this->name = $n; }
    public function setDescription(?string $d) { $this->description = $d; }
}
