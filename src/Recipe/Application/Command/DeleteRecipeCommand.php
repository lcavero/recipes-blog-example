<?php

namespace App\Recipe\Application\Command;

use App\Recipe\Domain\RecipeId;
use App\Shared\Application\Validation\MessageValidationTrait;
use App\Shared\Infrastructure\CQRS\Command;
use Symfony\Component\Validator\Constraints as Assert;

class DeleteRecipeCommand implements Command
{
    use MessageValidationTrait;

    private RecipeId $recipeId;

    private function __construct(string $id)
    {
        $this->recipeId = RecipeId::fromString($id);
    }

    public static function create(string $id): static
    {
        return new static($id);
    }

    public function recipeId(): RecipeId
    {
        return $this->recipeId;
    }

    private function validationConstrains() : Assert\Collection
    {
        return new Assert\Collection([]);
    }
}
