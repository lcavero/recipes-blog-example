<?php

namespace App\Recipe\Application\Query;

use App\Recipe\Domain\Ingredient;
use App\Recipe\Domain\Recipe;

class RecipeViewDTO
{
    private function __construct(
        private string $id,
        private string $name,
        private ?string $description,
        private array $ingredients
    )
    {}

    public static function fromEntity(Recipe $recipe): static
    {
        return new static(
            $recipe->id()->toString(),
            $recipe->name(),
            $recipe->description(),
            array_map(fn(Ingredient $ingredient) => IngredientViewDTO::fromEntity($ingredient), $recipe->ingredients())
        );
    }

    public function id(): string
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function description(): string
    {
        return $this->description;
    }

    public function ingredients(): array
    {
        return $this->ingredients;
    }
}
