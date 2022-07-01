<?php

namespace App\Recipe\Application\Query;

use App\Recipe\Domain\Ingredient;

class IngredientViewDTO
{
    private function __construct(private string $id, private string $description)
    {}

    public static function fromEntity(Ingredient $ingredient): static
    {
        return new static($ingredient->ingredientId()->toString(), $ingredient->description());
    }

    public function id(): string
    {
        return $this->id;
    }

    public function description(): string
    {
        return $this->description;
    }
}
