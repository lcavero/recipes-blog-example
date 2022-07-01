<?php

namespace App\Recipe\Application\Command;

use App\Recipe\Application\Service\RecipeDeleter;
use App\Shared\Infrastructure\CQRS\CommandHandler;

class DeleteRecipeCommandHandler implements CommandHandler
{
    public function __construct(private RecipeDeleter $recipeDeleter)
    {}

    public function __invoke(DeleteRecipeCommand $command): void
    {
        $this->recipeDeleter->delete($command->recipeId());
    }
}
