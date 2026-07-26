<?php

declare(strict_types=1);

namespace App\BoundedContext\Booking\Domain\ValueObject;

enum BookingStatus: string
{
    case CONFIRMED = 'confirmed';
    case CANCELED = 'canceled';
}
