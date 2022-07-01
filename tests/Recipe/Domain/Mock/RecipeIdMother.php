<?php

namespace App\Tests\Recipe\Domain\Mock;

use App\Recipe\Domain\RecipeId;
use App\Shared\Domain\Identity\IdFactory;

class RecipeIdMother
{
    public static function random(): RecipeId
    {
        return RecipeId::fromId(IdFactory::create());
    }
}
