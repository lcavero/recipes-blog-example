<?php

namespace App\Recipe\Domain\Service;

use App\Recipe\Domain\Exception\RecipeNotFoundException;
use App\Recipe\Domain\Recipe;
use App\Recipe\Domain\RecipeId;
use App\Recipe\Domain\Repository\RecipeRepository;

class RecipeFinder
{
    public function __construct(private RecipeRepository $repository)
    {}

    public function findById(RecipeId $recipeId): Recipe
    {
        $recipe = $this->repository->findOne($recipeId);
        $this->ensureRecipeExists($recipeId, $recipe);
        return $recipe;
    }

    private function ensureRecipeExists(RecipeId $recipeId, ?Recipe $recipe): void
    {
        if (null === $recipe) {
            throw RecipeNotFoundException::fromRecipeId($recipeId);
        }
    }
}
