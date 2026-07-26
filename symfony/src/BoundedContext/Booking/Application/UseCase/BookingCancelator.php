<?php

declare(strict_types=1);

namespace App\BoundedContext\Booking\Application\UseCase;

use App\BoundedContext\Booking\Domain\Repository\BookingRepositoryInterface;
use App\BoundedContext\Session\Domain\Repository\SessionRepositoryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use App\BoundedContext\Booking\Domain\Event\BookingCanceledEvent;
use App\BoundedContext\Booking\Domain\ValueObject\BookingStatus;
use Symfony\Component\Uid\Uuid;
use InvalidArgumentException;
use LogicException;

use function Symfony\Component\Clock\now;

class BookingCancelator
{
    public function __construct(
        private readonly BookingRepositoryInterface $bookingRepository,
        private readonly SessionRepositoryInterface $sessionRepository,
        private readonly EventDispatcherInterface $eventDispatcher
    ) {}

    public function execute(string $bookingId): void
    {
        $booking = $this->bookingRepository->findById(new Uuid($bookingId));

        if ($booking === null) {
            throw new InvalidArgumentException('booking not exists');
        }

        if ($booking->getStatus() === BookingStatus::CANCELED) {
            throw new LogicException('Booking is already canceled');
        }

        $session = $this->sessionRepository->findById($booking->getSessionId());

        if ($session === null) {
            throw new InvalidArgumentException('session not exists');
        }

        if (now()->modify('+ 24 hours') > $session->getDate()) {
            throw new LogicException('Booking only can cancel with more than 24 hours');
        }

        $session->unReservePlaces($booking->getPlaces());
        $booking->cancel();

        $this->bookingRepository->save($booking);
        $this->sessionRepository->save($session);

        $this->eventDispatcher->dispatch(new BookingCanceledEvent($booking->getId()));
    }
}
