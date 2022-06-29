<?php

namespace App\Recipe\Application\Query;

use App\Recipe\Domain\Recipe;

class RecipeDTO
{
    private function __construct(private string $id, private string $name)
    {}

    public static function fromEntity(Recipe $recipe): static
    {
        return new static($recipe->id()->value(), $recipe->name());
    }

    public function id(): string
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }
}
