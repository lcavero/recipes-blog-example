<?php

namespace App\Recipe\Infrastructure\Controller;

use App\Recipe\Application\Command\CreateRecipeCommand;
use App\Recipe\Application\Command\UpdateRecipeCommand;
use App\Recipe\Application\Query\GetRecipeQuery;
use App\Recipe\Application\Query\GetRecipesQuery;
use App\Recipe\Domain\Exception\RecipeNotFoundException;
use App\Shared\Domain\Exception\InvalidIdentityException;
use App\Shared\Domain\Exception\ValidationException;
use App\Shared\Domain\Identity\IdFactory;
use App\Shared\Infrastructure\CQRS\CommandBus;
use App\Shared\Infrastructure\CQRS\QueryBus;
use App\Shared\Infrastructure\Exception\BadRequestHttpException;
use App\Shared\Infrastructure\Exception\ResourceNotFoundHttpException;
use App\Shared\Infrastructure\Serializer\Serializer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

#[Route('/api/recipes', name: 'recipes_')]
class RecipeApiController
{
    public function __construct(
        private QueryBus $queryBus,
        private CommandBus $commandBus,
        private Serializer $serializer
    )
    {}

    #[Route('/list', name: 'list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $recipes = $this->queryBus->handle(
            GetRecipesQuery::create()
        );

        return  JsonResponse::fromJsonString(
            $this->serializer->serialize($recipes, JsonEncoder::FORMAT),
            Response::HTTP_OK
        );
    }

    #[Route('/view/{id}', name: 'view', methods: ['GET'])]
    public function one(string $id): Response
    {
        try {
            $recipe = $this->queryBus->handle(
                GetRecipeQuery::create($id)
            );

            return JsonResponse::fromJsonString(
                $this->serializer->serialize($recipe, JsonEncoder::FORMAT),
                Response::HTTP_OK
            );
        } catch (RecipeNotFoundException|InvalidIdentityException $e) {
            throw ResourceNotFoundHttpException::fromMessage($e->getMessage(), $e);
        }
    }

    #[Route('/new', name: 'create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $recipeId = IdFactory::create();

        try {
            $this->commandBus->dispatch(
                CreateRecipeCommand::create(
                    $recipeId->toString(),
                    $request->request->all()
                )
            );

        } catch (ValidationException $e) {
            throw BadRequestHttpException::fromErrors($e->getMessages(), $e);
        }

        return new JsonResponse(['id' => $recipeId->toString()], Response::HTTP_CREATED);
    }

    #[Route('/update/{id}', name: 'update', methods: ['PUT'])]
    public function update(string $id, Request $request): JsonResponse
    {
        try {
            $this->commandBus->dispatch(
                UpdateRecipeCommand::create(
                    $id,
                    $request->request->all()
                )
            );
        } catch (RecipeNotFoundException|InvalidIdentityException $e) {
            throw ResourceNotFoundHttpException::fromMessage($e->getMessage(), $e);
        } catch (ValidationException $e) {
            throw BadRequestHttpException::fromErrors($e->getMessages(), $e);
        }

        return new JsonResponse(['id' => $id], Response::HTTP_OK);
    }
}
