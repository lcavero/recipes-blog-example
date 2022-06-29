<?php

namespace App\Recipe\Domain;

use App\Recipe\Domain\Event\RecipeWasCreatedEvent;
use App\Shared\Domain\Aggregate\AggregateRoot;
use Webmozart\Assert\Assert;

class Recipe extends AggregateRoot
{
    private string $id;
    private string $name;

    public function __construct(RecipeId $recipeId, string $name)
    {
        Assert::lengthBetween($name, 1, 255);

        $this->id = $recipeId->value();
        $this->name = $name;
    }

    public static function create(RecipeId $recipeId, string $name): static
    {
        $recipe = new static($recipeId, $name);

        $recipe->record(RecipeWasCreatedEvent::create($recipeId->value(), $name));

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
}
