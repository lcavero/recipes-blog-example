<?php

namespace App\Recipe\Infrastructure\Controller;

use App\Recipe\Domain\Recipe;
use App\Recipe\Domain\RecipeId;
use App\Recipe\Domain\Repository\RecipeRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/recipes', name: 'recipes_')]
class RecipeApiController
{
    public function __construct(private RecipeRepository $repository)
    {}

    #[Route('', name: 'list', methods: ['GET'])]
    public function status(): JsonResponse
    {
        return new JsonResponse([], Response::HTTP_OK);
    }

    #[Route('/new', name: 'create', methods: ['GET'])]
    public function create(): JsonResponse
    {
        $recipe = Recipe::create(RecipeId::fromString($this->repository->nextIdentifier()), 'Foo');

        return new JsonResponse(['id' => $recipe->id()->value(), 'name' => $recipe->name()], Response::HTTP_OK);
    }
}
