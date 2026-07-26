<?php

declare(strict_types=1);

namespace App\BoundedContext\Session\Application\Request;

use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

class SessionCreatorRequest
{
    public function __construct(

        #[Assert\NotNull]
        public Uuid $experienceId,

        #[Assert\NotNull]
        public string $date,

        #[Assert\Positive]
        public int $maxCapacity,

        #[Assert\GreaterThanOrEqual(0)]
        public float $price
    ) {}
}
