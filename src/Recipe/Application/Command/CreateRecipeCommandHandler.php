<?php

namespace App\Recipe\Application\Command;

use App\Recipe\Domain\Recipe;
use App\Recipe\Domain\Repository\RecipeRepository;
use App\Shared\Infrastructure\CQRS\CommandHandler;

class CreateRecipeCommandHandler implements CommandHandler
{
    public function __construct(private RecipeRepository $repository)
    {}

    public function __invoke(CreateRecipeCommand $command): void
    {
        $this->repository->create(
            Recipe::create(
                $command->recipeId(),
                $command->name()
            )
        );
    }
}
