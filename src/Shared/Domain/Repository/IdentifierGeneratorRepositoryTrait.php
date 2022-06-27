<?php

namespace App\Shared\Domain\Repository;

use Symfony\Component\Uid\Uuid;

trait IdentifierGeneratorRepositoryTrait
{
    public function nextIdentifier(): string
    {
        return Uuid::v4()->toBase32();
    }
}
