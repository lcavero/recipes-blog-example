<?php

namespace App\Recipe\Application\Query;

use App\Recipe\Domain\Repository\RecipeRepository;
use App\Shared\Infrastructure\CQRS\QueryHandler;

class GetRecipesQueryHandler implements QueryHandler
{
    public function __construct(private RecipeRepository $repository)
    {}

    public function __invoke(GetRecipesQuery $query): array
    {
        return $this->repository->findAll();
    }
}
