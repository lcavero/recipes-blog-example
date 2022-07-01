<?php

namespace App\Recipe\Domain;

use App\Recipe\Domain\Event\RecipeWasCreatedEvent;
use App\Shared\Domain\Aggregate\AggregateRoot;
use App\Shared\Domain\Validation\DomainValidationTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;

class Recipe extends AggregateRoot
{
    use DomainValidationTrait;

    private string $id;
    private string $name;
    private ?string $description;
    private Collection $ingredients;

    public function __construct(RecipeId $recipeId, string $name, ?string $description)
    {
        $this->validate(['name' => $name, 'description' => $description]);

        $this->id = $recipeId->toString();
        $this->name = $name;
        $this->description = $description;
        $this->ingredients = new ArrayCollection();
    }

    protected function validationConstrains() : Assert\Collection
    {
        return new Assert\Collection([
            'name' => [
                new Assert\NotBlank(),
                new Assert\Length(['min' => 1, 'max' => 255])
            ],
            'description' => [
                new Assert\Optional(
                    new Assert\Length(['max' => 1000])
                )
            ]
        ]);
    }

    public static function create(RecipeId $recipeId, string $name, ?string $description): static
    {
        $recipe = new static($recipeId, $name, $description);

        $recipe->record(RecipeWasCreatedEvent::create($recipeId->toString(), $name));

        return $recipe;
    }

    public function id(): RecipeId
    {
        return RecipeId::fromString($this->id);
    }

    public function name(): string
    {
        return $this->name;
    }

    public function description(): ?string
    {
        return $this->description;
    }

    public function ingredients(): array
    {
        return $this->ingredients->toArray();
    }

    public function addIngredient(IngredientId $ingredientId, string $description): void
    {
        $this->ingredients->add(Ingredient::create($this, $ingredientId, $description));
    }

    public function removeIngredient(IngredientId $ingredientId): void
    {
        $ingredients = $this->ingredients->filter(fn(Ingredient $ingredient) => $ingredient->ingredientId()->equals($ingredientId));
        if ($ingredient = $ingredients->first()) {
            $this->ingredients->removeElement($ingredient);
        }
    }
}
