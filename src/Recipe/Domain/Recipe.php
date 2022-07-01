<?php

namespace App\Recipe\Domain;

use App\Recipe\Domain\Event\RecipeWasCreatedEvent;
use App\Recipe\Domain\Event\RecipeWasDeletedEvent;
use App\Recipe\Domain\Event\RecipeWasUpdatedEvent;
use App\Shared\Domain\Aggregate\AggregateRoot;
use App\Shared\Domain\Identity\IdFactory;
use App\Shared\Domain\Validation\DomainValidationTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Webmozart\Assert\Assert as Assertion;

class Recipe extends AggregateRoot
{
    use DomainValidationTrait;

    private string $id;
    private string $name;
    private ?string $description;
    private Collection $ingredients;

    private function __construct(RecipeId $recipeId, string $name, ?string $description, array $ingredients)
    {
        $this->validate(['name' => $name, 'description' => $description, 'ingredients' => $ingredients]);

        $this->id = $recipeId->toString();
        $this->name = $name;
        $this->description = $description;
        $this->ingredients = new ArrayCollection();

        foreach ($ingredients as $ingredient) {
            Assertion::isArray($ingredient) && Assertion::keyExists($ingredient, 'description');
            $this->addIngredient(
                Ingredient::create($this, IngredientId::fromId(IdFactory::create()), $ingredient['description'])
            );
        }
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
            ],
            'ingredients' => [
                new Assert\Count(['min' => 1])
            ]
        ]);
    }

    public static function create(RecipeId $recipeId, string $name, ?string $description, array $ingredients): static
    {
        $recipe = new static($recipeId, $name, $description, $ingredients);

        $recipe->record(RecipeWasCreatedEvent::create($recipeId->toString(), $name, $description, $recipe->ingredients()));

        return $recipe;
    }

    public function update(string $name, ?string $description, array $ingredients): void
    {
        $this->validate(['name' => $name, 'description' => $description, 'ingredients' => $ingredients]);
        $this->name = $name;
        $this->description = $description;

        foreach ($this->ingredients as $ingredient) {
            $this->removeIngredient($ingredient);
        }


        foreach ($ingredients as $ingredient) {
            Assertion::isArray($ingredient) && Assertion::keyExists($ingredient, 'description');
            $this->addIngredient(
                Ingredient::create($this, IngredientId::fromId(IdFactory::create()), $ingredient['description'])
            );
        }

        $this->record(RecipeWasUpdatedEvent::create($this->id, $name, $description, $this->ingredients()));
    }

    public function delete(): void
    {
        $this->record(RecipeWasDeletedEvent::create($this->id, $this->name, $this->description, $this->ingredients()));
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
        return array_values($this->ingredients->toArray());
    }

    private function addIngredient(Ingredient $ingredient): void
    {
        $this->ingredients->add($ingredient);
    }

    private function removeIngredient(Ingredient $ingredient): void
    {
        $this->ingredients->removeElement($ingredient);
    }
}
