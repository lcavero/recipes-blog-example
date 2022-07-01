<?php

namespace App\Recipe\Application\Service;

use App\Recipe\Domain\IngredientId;
use App\Recipe\Domain\Recipe;
use App\Recipe\Domain\RecipeId;
use App\Recipe\Domain\Repository\RecipeRepository;
use App\Shared\Domain\Identity\IdFactory;
use App\Shared\Infrastructure\CQRS\EventBus;

class RecipeCreator
{
    public function __construct(private RecipeRepository $repository, private EventBus $eventBus)
    {}

    public function create(RecipeId $recipeId, string $name, ?string $description, array $ingredients)
    {
        $recipe = Recipe::create(
            $recipeId,
            $name,
            $description
        );

        array_walk($ingredients, fn(array $ingredient) => $recipe->addIngredient(
            IngredientId::fromId(IdFactory::create()),
            $ingredient['description']
        ));

        $this->repository->create(
            $recipe
        );

        $this->eventBus->dispatch(...$recipe->pullDomainEvents());
    }
}
