<?php

declare(strict_types=1);

namespace App\BoundedContext\Session\Infrastructure\Service;

use App\BoundedContext\Session\Domain\Repository\SessionRepositoryInterface;
use App\BoundedContext\Session\Domain\Service\ExperienceSessionInDateCheckerInterface;
use DateTimeImmutable;
use Symfony\Component\Uid\Uuid;

final class ExperienceSessionInDateChecker implements ExperienceSessionInDateCheckerInterface
{
    public function __construct(
        private readonly SessionRepositoryInterface $sessionRepository
    ) {}

    public function existsSessionInDate(Uuid $experienceId, DateTimeImmutable $date): bool
    {
        return $this->sessionRepository->findByExperienceAndDate($experienceId, $date) !== null;
    }
}
