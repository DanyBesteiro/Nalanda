<?php

declare(strict_types=1);

namespace App\BoundedContext\Experience\Domain\Repository;

use App\BoundedContext\Experience\Domain\Entity\Experience;
use Symfony\Component\Uid\Uuid;

interface ExperienceRepositoryInterface
{
    public function save(Experience $experience): void;

    public function findById(Uuid $id): ?Experience;
}
