<?php

declare(strict_types=1);

namespace App\BoundedContext\Booking\Domain\Repository;

use App\BoundedContext\Booking\Domain\Entity\Booking;
use Symfony\Component\Uid\Uuid;

interface BookingRepositoryInterface
{
    public function save(Booking $booking): void;

    public function findById(Uuid $id): ?Booking;
}
