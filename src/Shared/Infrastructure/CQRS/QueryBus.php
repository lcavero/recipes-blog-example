<?php

namespace App\Shared\Infrastructure\CQRS;

interface QueryBus
{
    public function handle(Query $query): mixed;
}
