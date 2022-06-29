<?php

namespace App\Recipe\Application\Command;

use App\Recipe\Application\Service\RecipeCreator;
use App\Shared\Infrastructure\CQRS\CommandHandler;

class CreateRecipeCommandHandler implements CommandHandler
{
    public function __construct(private RecipeCreator $recipeCreator)
    {}

    public function __invoke(CreateRecipeCommand $command): void
    {
        $this->recipeCreator->create($command->recipeId(), $command->name());
    }
}
