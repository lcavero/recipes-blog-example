<?php

namespace App\Recipe\Application\Command;

use App\Recipe\Application\Service\RecipeUpdater;
use App\Shared\Infrastructure\CQRS\CommandHandler;

class UpdateRecipeCommandHandler implements CommandHandler
{
    public function __construct(private RecipeUpdater $recipeUpdater)
    {}

    public function __invoke(UpdateRecipeCommand $command): void
    {
        $this->recipeUpdater->update(
            $command->recipeId(),
            $command->name(),
            $command->description(),
            $command->ingredients()
        );
    }
}
