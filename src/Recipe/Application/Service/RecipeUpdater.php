<?php

namespace App\Recipe\Application\Service;

use App\Recipe\Domain\RecipeId;
use App\Recipe\Domain\Repository\RecipeRepository;
use App\Recipe\Domain\Service\RecipeFinder;
use App\Shared\Infrastructure\CQRS\EventBus;

class RecipeUpdater
{
    public function __construct(private RecipeFinder $recipeFinder, private RecipeRepository $repository, private EventBus $eventBus)
    {}

    public function update(RecipeId $recipeId, string $name, ?string $description, array $ingredients): void
    {
        $recipe = $this->recipeFinder->findById($recipeId);

        $recipe->update($name, $description, $ingredients);

        $this->repository->update(
            $recipe
        );

        $this->eventBus->dispatch(...$recipe->pullDomainEvents());
    }
}
