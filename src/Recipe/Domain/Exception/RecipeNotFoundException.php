<?php

namespace App\Recipe\Domain\Exception;

use App\Recipe\Domain\RecipeId;
use Exception;

class RecipeNotFoundException extends Exception
{
    private function __construct(string $message)
    {
        parent::__construct($message);
    }

    public static function fromRecipeId(RecipeId $recipeId): static
    {
        return new static(sprintf('The Recipe with id "%s" was not found', $recipeId->value()));
    }
}
