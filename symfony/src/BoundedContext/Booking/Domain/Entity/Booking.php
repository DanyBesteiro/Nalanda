<?php

declare(strict_types=1);

namespace App\BoundedContext\Booking\Domain\Entity;

use App\BoundedContext\Booking\Domain\ValueObject\BookingStatus;

use Doctrine\ORM\Mapping as ORM;
use LogicException;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
#[ORM\Table(name: 'booking')]
class Booking
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid')]
    private Uuid $id;

    #[ORM\Column(type: 'uuid', name: 'session_id')]
    private Uuid $sessionId;

    #[ORM\Column(type: 'uuid', name: 'user_id')]
    private Uuid $userId;

    #[ORM\Column(type: 'string', length: 255, enumType: BookingStatus::class)]
    private BookingStatus $status;

    #[ORM\Column(type: 'integer')]
    private int $places;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2, name: 'total_price')]
    private readonly float $totalPrice;

    public function __construct(
        Uuid $sessionId,
        Uuid $userId,
        int $places,
        float $totalPrice
    ) {

        $this->id = Uuid::v4();
        $this->sessionId = $sessionId;
        $this->userId = $userId;

        $this->status = BookingStatus::CONFIRMED;
        $this->places = $places;
        $this->totalPrice = $totalPrice;
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getSessionId(): Uuid
    {
        return $this->sessionId;
    }

    public function getUserId(): Uuid
    {
        return $this->userId;
    }

    public function getStatus(): BookingStatus
    {
        return $this->status;
    }

    public function getPlaces(): int
    {
        return $this->places;
    }

    public function getTotalPrice(): float
    {
        return $this->totalPrice;
    }

    public function cancelBooking(): void
    {
        if ($this->status === BookingStatus::CANCELED) {
            throw new LogicException('booking already canceled');
        }

        $this->status = BookingStatus::CANCELED;
    }
}
