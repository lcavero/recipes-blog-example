<?php

namespace App\Shared\Domain\Repository;

interface Repository
{
    public function nextIdentifier(): string;
}
