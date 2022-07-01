<?php

namespace App\Tests\Recipe\Domain;

use App\Recipe\Domain\Event\RecipeWasDeletedEvent;
use App\Recipe\Domain\Recipe;
use PHPUnit\Framework\TestCase;

class DeleteRecipeTest extends TestCase
{
    private Recipe $recipe;

    public function testDelete(): void
    {
        $this->recipe->delete();

        $domainEvents = $this->recipe->pullDomainEvents();
        $this->assertCount(1, $domainEvents);
        $this->assertInstanceOf(RecipeWasDeletedEvent::class, $domainEvents[0]);
    }

    /**
     * @before
     */
    public function setupRecipe(): void
    {
        $recipeId = RecipeIdMother::random();
        $ingredients = [
            ['description' => '3 Apples'],
            ['description' => '2 Eggs'],
            ['description' => '50g Sugar']
        ];
        $recipe = Recipe::create($recipeId, 'Apple Pie', 'A nice Apple Pie', $ingredients);
        $recipe->pullDomainEvents();
        $this->recipe = $recipe;
    }
}
