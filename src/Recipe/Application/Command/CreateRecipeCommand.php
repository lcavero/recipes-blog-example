<?php

namespace App\Recipe\Application\Command;

use App\Recipe\Domain\RecipeId;
use App\Shared\Domain\Validation\MessageValidationTrait;
use App\Shared\Infrastructure\CQRS\Command;
use Symfony\Component\Validator\Constraints as Assert;


class CreateRecipeCommand implements Command
{
    use MessageValidationTrait;

    private RecipeId $recipeId;
    private array $payload;

    private function __construct(string $id, array $payload)
    {
        $this->recipeId = RecipeId::fromString($id);
        $this->validate($payload);
        $this->payload = $payload;
    }

    public static function create(string $id, array $payload): static
    {
        return new static($id, $payload);
    }

    public function recipeId(): RecipeId
    {
        return $this->recipeId;
    }

    public function name(): string
    {
        return $this->payload['name'];
    }

    protected function validationConstrains() : Assert\Collection
    {
        return new Assert\Collection([
            'name' => [
                new Assert\NotBlank(),
                new Assert\Length([
                    'min' => 5,
                    'max' => 255
                ]),
            ],
        ]);
    }
}
