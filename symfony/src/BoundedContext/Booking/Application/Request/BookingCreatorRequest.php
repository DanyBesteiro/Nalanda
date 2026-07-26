<?php

declare(strict_types=1);

namespace App\BoundedContext\Booking\Application\Request;

use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

class BookingCreatorRequest
{
    public function __construct(
        #[Assert\NotBlank]
        public Uuid $sessionId,

        #[Assert\NotNull]
        public Uuid $userId,

        #[Assert\Positive()]
        public int $places
    ) {}
}
