<?php

declare(strict_types=1);

namespace App\BoundedContext\Session\Domain\Repository;

use App\BoundedContext\Session\Domain\Entity\Session;
use Symfony\Component\Uid\Uuid;

interface SessionRepositoryInterface
{
    public function save(Session $session): void;

    public function findById(Uuid $id): ?Session;
}
