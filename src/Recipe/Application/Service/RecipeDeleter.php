<?php

namespace App\Recipe\Application\Service;

use App\Recipe\Domain\RecipeId;
use App\Recipe\Domain\Repository\RecipeRepository;
use App\Recipe\Domain\Service\RecipeFinder;
use App\Shared\Infrastructure\CQRS\EventBus;

class RecipeDeleter
{
    public function __construct(private RecipeFinder $recipeFinder, private RecipeRepository $repository, private EventBus $eventBus)
    {}

    public function delete(RecipeId $recipeId): void
    {
        $recipe = $this->recipeFinder->findById($recipeId);

        $recipe->delete();

        $events = $recipe->pullDomainEvents();

        $this->repository->delete(
            $recipe
        );

        $this->eventBus->dispatch(...$events);
    }
}
