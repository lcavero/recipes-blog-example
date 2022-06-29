<?php

namespace App\Recipe\Application\Query;

use App\Recipe\Domain\RecipeId;
use App\Shared\Infrastructure\CQRS\Query;

class GetRecipeQuery implements Query
{
    private RecipeId $recipeId;

    private function __construct(string $recipeId)
    {
        $this->recipeId = RecipeId::fromString($recipeId);
    }

    public static function create(string $recipeId): static
    {
        return new static($recipeId);
    }

    public function recipeId(): RecipeId
    {
        return $this->recipeId;
    }
}
