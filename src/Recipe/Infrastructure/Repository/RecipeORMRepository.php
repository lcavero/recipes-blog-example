<?php

namespace App\Recipe\Infrastructure\Repository;

use App\Recipe\Domain\Recipe;
use App\Recipe\Domain\RecipeId;
use App\Recipe\Domain\Repository\RecipeRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class RecipeORMRepository extends ServiceEntityRepository implements RecipeRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recipe::class);
    }

    public function findAll(): array
    {
        return parent::findAll();
    }

    public function findOne(RecipeId $recipeId): ?Recipe
    {
        return $this->find($recipeId->toString());
    }

    public function create(Recipe $recipe): void
    {
        $this->_em->persist($recipe);
    }

    public function update(Recipe $recipe): void
    {
        return;
    }
}
