<?php

namespace App\Recipe\Domain;

use App\Recipe\Domain\Event\RecipeWasCreatedEvent;
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

    public static function create(RecipeId $recipeId, string $name, ?string $description, array $ingredients): static
    {
        $recipe = new static($recipeId, $name, $description);
        foreach ($ingredients as $ingredient) {
            Assertion::isArray($ingredient) && Assertion::keyExists($ingredient, 'description');
            $recipe->addIngredient(
                Ingredient::create($recipe, IngredientId::fromId(IdFactory::create()), $ingredient['description'])
            );
        }
        $recipe->record(RecipeWasCreatedEvent::create($recipeId->toString(), $name, $description, $recipe->ingredients()));

        return $recipe;
    }

    public function update(string $name, ?string $description, array $ingredients): void
    {
        $this->validate(['name' => $name, 'description' => $description]);
        $this->name = $name;
        $this->description = $description;

        $this->ingredients->clear();

        foreach ($ingredients as $ingredient) {
            Assertion::isArray($ingredient) && Assertion::keyExists($ingredient, 'description');
            $this->addIngredient(
                Ingredient::create($this, IngredientId::fromId(IdFactory::create()), $ingredient['description'])
            );
        }

        $this->record(RecipeWasCreatedEvent::create($this->id, $name, $description, $this->ingredients()));
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

    public function addIngredient(Ingredient $ingredient): void
    {
        $this->ingredients->add($ingredient);
    }

    public function removeIngredient(Ingredient $ingredient): void
    {
        $this->ingredients->removeElement($ingredient);
    }
}
