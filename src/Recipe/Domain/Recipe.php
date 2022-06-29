<?php

namespace App\Recipe\Domain;

use Webmozart\Assert\Assert;

class Recipe
{
    private string $id;
    private string $name;

    private function __construct(RecipeId $recipeId, string $name)
    {
        Assert::lengthBetween($name, 1, 255);

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
