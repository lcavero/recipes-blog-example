<?php

namespace App\Tests\Recipe\Domain;

use App\Recipe\Domain\Event\RecipeWasUpdatedEvent;
use App\Recipe\Domain\Ingredient;
use App\Recipe\Domain\Recipe;
use App\Shared\Domain\Exception\DomainValidationException;
use App\Tests\Recipe\Domain\Mock\RecipeIdMother;
use Lib\Random\RandomStringGenerator;
use PHPUnit\Framework\TestCase;

class UpdateRecipeTest extends TestCase
{
    private Recipe $recipe;

    public function testUpdateFullFields(): void
    {
        $ingredients = [
            ['description' => '3 Pears'],
            ['description' => '2 Eggs'],
            ['description' => '500ml Milk']
        ];
        $this->recipe->update('Pear Pie', 'A nice Pear Pie', $ingredients);

        $this->assertSame('Pear Pie', $this->recipe->name());
        $this->assertSame('A nice Pear Pie', $this->recipe->description());

        $this->assertNotEmpty($this->recipe->ingredients());
        $this->assertCount(count($ingredients), $this->recipe->ingredients());

        /** @var Ingredient $ingredient */
        foreach ($this->recipe->ingredients() as $key => $ingredient) {
            $this->assertInstanceOf(Ingredient::class, $ingredient);
            $this->assertSame($ingredients[$key]['description'], $ingredient->description());
        }

        $domainEvents = $this->recipe->pullDomainEvents();
        $this->assertCount(1, $domainEvents);
        $this->assertInstanceOf(RecipeWasUpdatedEvent::class, $domainEvents[0]);
    }

    public function testUpdateOptionalFields(): void
    {
        $ingredients = [
            ['description' => '3 Apples'],
            ['description' => '2 Eggs'],
            ['description' => '50g Sugar']
        ];
        $this->recipe->update('Apple Pie', null, $ingredients);
        $this->assertNull($this->recipe->description());
    }

    public function testUpdateWithoutIngredients(): void
    {
        try {
            $this->recipe->update('Apple Pie', 'A nice Apple Pie', []);
            $this->fail('DomainValidationException was not thrown');
        } catch (DomainValidationException $e) {
            $this->assertSame([
                'ingredients' => ['This collection should contain 1 element or more.']
            ], $e->getMessages());
        }
    }

    public function testUpdateWithoutName(): void
    {
        $ingredients = [
            ['description' => '3 Apples'],
        ];
        try {
            $this->recipe->update('', null, $ingredients);
            $this->fail('DomainValidationException was not thrown');
        } catch (DomainValidationException $e) {
            $this->assertSame([
                'name' => ['This value should not be blank.', 'This value is too short. It should have 1 character or more.']
            ], $e->getMessages());
        }
    }

    public function testUpdateExceedingLimits(): void
    {
        $ingredients = [
            ['description' => '3 Apples'],
        ];
        try {
            $this->recipe->update(RandomStringGenerator::generate(256), RandomStringGenerator::generate(1001), $ingredients);
            $this->fail('DomainValidationException was not thrown');
        } catch (DomainValidationException $e) {
            $this->assertSame([
                'name' => ['This value is too long. It should have 255 characters or less.'],
                'description' => ['This value is too long. It should have 1000 characters or less.'],
            ], $e->getMessages());
        }
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
