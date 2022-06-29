<?php

namespace App\Recipe\Application\Query;

use App\Recipe\Domain\Service\RecipeFinder;
use App\Shared\Infrastructure\CQRS\QueryHandler;

class GetRecipeQueryHandler implements QueryHandler
{
    public function __construct(private RecipeFinder $recipeFinder)
    {}

    public function __invoke(GetRecipeQuery $query): RecipeDTO
    {
        return RecipeDTO::fromEntity(
            $this->recipeFinder->findById($query->recipeId())
        );
    }
}
