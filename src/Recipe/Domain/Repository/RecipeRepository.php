<?php

namespace App\Recipe\Domain\Repository;

use App\Recipe\Domain\Recipe;
use App\Recipe\Domain\RecipeId;

interface RecipeRepository
{
    public function findAll(): array;
    public function findOne(RecipeId $recipeId): ?Recipe;
    public function create(Recipe $recipe): void;
    public function update(Recipe $recipe): void;
    public function delete(Recipe $recipe): void;
}
