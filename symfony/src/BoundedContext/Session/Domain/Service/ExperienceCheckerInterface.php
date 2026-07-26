<?php

declare(strict_types=1);

namespace App\BoundedContext\Session\Domain\Service;

use Symfony\Component\Uid\Uuid;

interface ExperienceCheckerInterface
{
    public function exists(Uuid $experienceId): bool;
}
