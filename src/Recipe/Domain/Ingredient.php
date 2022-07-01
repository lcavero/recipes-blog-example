<?php

namespace App\Recipe\Domain;

use App\Shared\Domain\Validation\DomainValidationTrait;
use Symfony\Component\Validator\Constraints as Assert;

class Ingredient
{
    use DomainValidationTrait;

    private string $id;
    private string $description;
    private Recipe $recipe;

    public function __construct(Recipe $recipe, IngredientId $ingredientId, string $description)
    {
        $this->validate(['description' => $description]);
        $this->recipe = $recipe;
        $this->id = $ingredientId->toString();
        $this->description = $description;
    }

    protected function validationConstrains() : Assert\Collection
    {
        return new Assert\Collection([
            'description' => [
                new Assert\Length(['min' => 2, 'max' => 255])
            ]
        ]);
    }

    public static function create(Recipe $recipe, IngredientId $ingredientId, string $description): static
    {
        return new static($recipe, $ingredientId, $description);
    }

    public function ingredientId(): IngredientId
    {
        return IngredientId::fromString($this->id);
    }

    public function recipe(): Recipe
    {
        return $this->recipe;
    }

    public function description(): string
    {
        return $this->description;
    }
}
