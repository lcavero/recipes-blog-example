<?php

namespace App\Recipe\Application\Command;

use App\Recipe\Domain\RecipeId;
use App\Shared\Application\Validation\MessageValidationTrait;
use App\Shared\Infrastructure\CQRS\Command;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateRecipeCommand implements Command
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

    public function description(): ?string
    {
        return $this->payload['description'] ?? null;
    }

    public function ingredients(): array
    {
        return $this->payload['ingredients'] ?? [];
    }

    private function validationConstrains() : Assert\Collection
    {
        return new Assert\Collection([
            'name' => [
                new Assert\NotBlank(),
            ],
            'description' => [
                new Assert\Optional(
                    new Assert\Type('string')
                )
            ],
            'ingredients' => [
                new Assert\Type('array'),
                new Assert\Count(['min' => 1]),
                new Assert\All([
                    new Assert\Collection([
                        'description' => [
                            new Assert\NotBlank(),
                            new Assert\Type('string')
                        ]
                    ])
                ])
            ]
        ]);
    }
}
