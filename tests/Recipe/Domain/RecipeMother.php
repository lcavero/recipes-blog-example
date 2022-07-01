<?php

namespace App\Tests\Recipe\Domain;

use App\Recipe\Domain\Recipe;

class RecipeMother
{
    public static function any(): Recipe
    {
        return Recipe::create(RecipeIdMother::random(), 'Any', 'Any', ['description' => 'Any']);
    }
}
