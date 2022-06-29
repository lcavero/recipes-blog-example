<?php

namespace App\Shared\Infrastructure\CQRS;

use App\Shared\Domain\Event\DomainEvent;

interface EventBus
{
    public function dispatch(DomainEvent $event): void;
}
