<?php

declare(strict_types=1);

namespace App\BoundedContext\Booking\Application\DTO;

use App\BoundedContext\Booking\Domain\Entity\Booking;
use Symfony\Component\Uid\Uuid;

class BookingDTO
{
    public function __construct(
        public Uuid $id,
        public Uuid $sessionId,
        public Uuid $userId,
        public string $status,
        public int $places,
        public float $totalPrice
    ) {}

    /**
     * @return array{
     * id: string,
     * sessionId: string,
     * userId: string,
     * status: string,
     * places: int,
     * totalPrice: float
     * }
     */
    public static function fromEntity(Booking $booking): array
    {
        return [
            'id' => $booking->getId()->toRfc4122(),
            'sessionId' => $booking->getSessionId()->toRfc4122(),
            'userId' => $booking->getUserId()->toRfc4122(),
            'status' => $booking->getStatus()->value,
            'places' => $booking->getPlaces(),
            'totalPrice' => $booking->getTotalPrice()
        ];
    }
}
