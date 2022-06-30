<?php

namespace App\Recipe\Application\Service;

use App\Recipe\Domain\Recipe;
use App\Recipe\Domain\RecipeId;
use App\Recipe\Domain\Repository\RecipeRepository;
use App\Shared\Infrastructure\CQRS\EventBus;

class RecipeCreator
{
    public function __construct(private RecipeRepository $repository, private EventBus $eventBus)
    {}

    public function create(RecipeId $recipeId, string $name, ?string $description)
    {
        $recipe = Recipe::create(
            $recipeId,
            $name,
            $description
        );

        $this->repository->create(
            $recipe
        );

        $this->eventBus->dispatch(...$recipe->pullDomainEvents());
    }
}
