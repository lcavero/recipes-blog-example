<?php

namespace App\Recipe\Domain;

class Recipe
{
    private string $id;
    private string $name;

    private function __construct(RecipeId $recipeId, string $name)
    {
        $this->id = $recipeId->value();
        $this->name = $name;
    }

    public static function create(RecipeId $recipeId, string $name): static
    {
        return new static($recipeId, $name);
    }

    public function id(): RecipeId
    {
        return RecipeId::fromString($this->id);
    }

    public function name(): string
    {
        return $this->name;
    }
}
