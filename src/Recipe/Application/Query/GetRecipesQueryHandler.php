<?php

namespace App\Recipe\Application\Query;

use App\Recipe\Domain\Recipe;
use App\Recipe\Domain\Repository\RecipeRepository;
use App\Shared\Infrastructure\CQRS\QueryHandler;

class GetRecipesQueryHandler implements QueryHandler
{
    public function __construct(private RecipeRepository $repository)
    {}

    public function __invoke(GetRecipesQuery $query): array
    {
        $recipes = $this->repository->findAll();
        return array_map(fn(Recipe $recipe) => RecipeListDTO::fromEntity($recipe), $recipes);
    }
}
