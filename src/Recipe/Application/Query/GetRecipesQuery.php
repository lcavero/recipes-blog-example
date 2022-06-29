<?php

namespace App\Recipe\Application\Query;

use App\Shared\Infrastructure\CQRS\Query;

class GetRecipesQuery implements Query
{
    private function __construct()
    {}

    public static function create(): static
    {
        return new static();
    }
}
