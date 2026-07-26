<?php

declare(strict_types=1);

namespace App\BoundedContext\Booking\Infrastructure\Listener;

use App\BoundedContext\Booking\Domain\Event\BookingCreatedEvent;

final class SendBookingToQueueListener
{
    public function __invoke(BookingCreatedEvent $event): void
    {
        $bookingId = $event->getBookingId()->toRfc4122();
    }
}
