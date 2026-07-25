<?php

declare(strict_types=1);

namespace App\BoundedContext\Session\Infrastructure\Service;

use App\BoundedContext\Experience\Domain\Repository\ExperienceRepositoryInterface;
use App\BoundedContext\Session\Domain\Service\ExperienceCheckerInterface;
use Symfony\Component\Uid\Uuid;

final class DoctrineExperienceChecker implements ExperienceCheckerInterface
{
    public function __construct(
        private readonly ExperienceRepositoryInterface $experienceRepository
    ) {}

    public function exists(Uuid $experienceId): bool
    {
        return $this->experienceRepository->findById($experienceId) !== null;
    }
}
