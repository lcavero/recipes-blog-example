<?php

namespace App\Shared\Infrastructure\CQRS;

interface CommandBus
{
    public function dispatch(Command $command): void;
}
