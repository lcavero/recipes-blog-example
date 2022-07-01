<?php

namespace App\Tests\Recipe\Domain;

use App\Recipe\Domain\Event\RecipeWasCreatedEvent;
use App\Recipe\Domain\Ingredient;
use App\Recipe\Domain\Recipe;
use App\Shared\Domain\Exception\DomainValidationException;
use App\Tests\Recipe\Domain\Mock\RecipeIdMother;
use Lib\Random\RandomStringGenerator;
use PHPUnit\Framework\TestCase;

class CreateRecipeTest extends TestCase
{
    public function testCreateFullFields(): void
    {
        $recipeId = RecipeIdMother::random();
        $ingredients = [
            ['description' => '3 Apples'],
            ['description' => '2 Eggs'],
            ['description' => '50g Sugar']
        ];
        $recipe = Recipe::create($recipeId, 'Apple Pie', 'A nice Apple Pie', $ingredients);
        $this->assertSame('Apple Pie', $recipe->name());
        $this->assertSame('A nice Apple Pie', $recipe->description());

        $this->assertNotEmpty($recipe->ingredients());
        $this->assertCount(count($ingredients), $recipe->ingredients());

        /** @var Ingredient $ingredient */
        foreach ($recipe->ingredients() as $key => $ingredient) {
            $this->assertInstanceOf(Ingredient::class, $ingredient);
            $this->assertSame($ingredients[$key]['description'], $ingredient->description());
        }
        $domainEvents = $recipe->pullDomainEvents();
        $this->assertCount(1, $domainEvents);
        $this->assertInstanceOf(RecipeWasCreatedEvent::class, $domainEvents[0]);
    }

    public function testCreateOptionalFields(): void
    {
        $recipeId = RecipeIdMother::random();
        $ingredients = [
            ['description' => '3 Apples'],
        ];
        $recipe = Recipe::create($recipeId, 'Apple Pie', null, $ingredients);
        $this->assertNull($recipe->description());
    }

    public function testCreateWithoutIngredients(): void
    {
        $recipeId = RecipeIdMother::random();
        try {
            Recipe::create($recipeId, 'Apple Pie', null, []);
            $this->fail('DomainValidationException was not thrown');
        } catch (DomainValidationException $e) {
            $this->assertSame([
                'ingredients' => ['This collection should contain 1 element or more.']
            ], $e->getMessages());
        }
    }

    public function testCreateWithoutName(): void
    {
        $recipeId = RecipeIdMother::random();
        $ingredients = [
            ['description' => '3 Apples'],
        ];
        try {
            Recipe::create($recipeId, '', null, $ingredients);
            $this->fail('DomainValidationException was not thrown');
        } catch (DomainValidationException $e) {
            $this->assertSame([
                'name' => ['This value should not be blank.', 'This value is too short. It should have 1 character or more.']
            ], $e->getMessages());
        }
    }

    public function testCreateExceedingLimits(): void
    {
        $recipeId = RecipeIdMother::random();
        $ingredients = [
            ['description' => '3 Apples'],
        ];
        try {
            Recipe::create($recipeId, RandomStringGenerator::generate(256), RandomStringGenerator::generate(1001), $ingredients);
            $this->fail('DomainValidationException was not thrown');
        } catch (DomainValidationException $e) {
            $this->assertSame([
                'name' => ['This value is too long. It should have 255 characters or less.'],
                'description' => ['This value is too long. It should have 1000 characters or less.'],
            ], $e->getMessages());
        }
    }
}
