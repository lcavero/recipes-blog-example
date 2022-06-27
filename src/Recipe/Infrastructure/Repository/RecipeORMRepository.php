<?php

namespace App\Recipe\Infrastructure\Repository;

use App\Recipe\Domain\Recipe;
use App\Recipe\Domain\Repository\RecipeRepository;
use App\Shared\Domain\Repository\IdentifierGeneratorRepositoryTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class RecipeORMRepository extends ServiceEntityRepository implements RecipeRepository
{
    use IdentifierGeneratorRepositoryTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recipe::class);
    }
}
