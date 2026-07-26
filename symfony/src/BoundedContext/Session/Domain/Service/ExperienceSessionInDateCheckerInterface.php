<?php

declare(strict_types=1);

namespace App\BoundedContext\Session\Domain\Service;

use DateTimeImmutable;
use Symfony\Component\Uid\Uuid;

interface ExperienceSessionInDateCheckerInterface
{
    public function existsSessionInDate(Uuid $experienceId, DateTimeImmutable $date): bool;
}
