<?php

declare(strict_types=1);

namespace App\BoundedContext\Session\Domain\Repository;

use App\BoundedContext\Session\Domain\Entity\Session;
use DateTimeImmutable;
use Symfony\Component\Uid\Uuid;

interface SessionRepositoryInterface
{
    public function save(Session $session): void;

    public function findById(Uuid $id): ?Session;

    public function findByExperienceAndDate(Uuid $experienceId, DateTimeImmutable $date): ?Session;
}
