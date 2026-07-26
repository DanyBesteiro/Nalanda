<?php

declare(strict_types=1);

namespace App\BoundedContext\Booking\Domain\Event;

use Symfony\Component\Uid\Uuid;

final class BookingCreatedEvent
{
    public function __construct(
        private readonly Uuid $bookingId
    ) {}

    public function getBookingId(): Uuid
    {
        return $this->bookingId;
    }
}
