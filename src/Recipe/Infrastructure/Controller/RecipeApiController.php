<?php

namespace App\Recipe\Infrastructure\Controller;

use App\Recipe\Application\Command\CreateRecipeCommand;
use App\Recipe\Application\Query\GetRecipeQuery;
use App\Recipe\Application\Query\GetRecipesQuery;
use App\Recipe\Domain\Exception\RecipeNotFoundException;
use App\Shared\Domain\Exception\MessageValidationException;
use App\Shared\Infrastructure\CQRS\CommandBus;
use App\Shared\Infrastructure\CQRS\QueryBus;
use App\Shared\Infrastructure\Exception\BadRequestHttpException;
use App\Shared\Infrastructure\Serializer\Serializer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Uid\Uuid;

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
        } catch (RecipeNotFoundException $e) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, $e->getMessage());
        }
    }

    #[Route('/new', name: 'create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $recipeId = Uuid::v4()->toBase32();

        try {
            $this->commandBus->dispatch(
                CreateRecipeCommand::create(
                    $recipeId,
                    $request->request->all()
                )
            );

        } catch (MessageValidationException $e) {
            throw BadRequestHttpException::fromErrors($e->getMessages());
        }

        return new JsonResponse(['id' => $recipeId], Response::HTTP_ACCEPTED);
    }
}
