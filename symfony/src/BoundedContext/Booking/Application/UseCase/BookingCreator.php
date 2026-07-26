<?php

declare(strict_types=1);

namespace App\BoundedContext\Booking\Application\UseCase;

use App\BoundedContext\Booking\Application\Request\BookingCreatorRequest;
use App\BoundedContext\Booking\Domain\Entity\Booking;
use App\BoundedContext\Booking\Domain\Event\BookingCreatedEvent;
use App\BoundedContext\Booking\Domain\Repository\BookingRepositoryInterface;
use App\BoundedContext\Session\Domain\Repository\SessionRepositoryInterface;
use InvalidArgumentException;
use LogicException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class BookingCreator
{
    public function __construct(
        private readonly BookingRepositoryInterface $repository,
        private readonly SessionRepositoryInterface $sessionRepository,
        private readonly EventDispatcherInterface $eventDispatcher
    ) {}

    public function execute(BookingCreatorRequest $request): Booking
    {
        $session = $this->sessionRepository->findById($request->sessionId);

        if (is_null($session)) {
            throw new InvalidArgumentException('Session not found');
        }

        if ($request->places > $session->getFreePlaces()) {
            throw new LogicException('Not enough places in this session');
        }

        $booking = new Booking(
            sessionId: $request->sessionId,
            userId: $request->userId,
            places: $request->places,
            totalPrice: $session->getPrice() * $request->places
        );
        $session->reservePlaces($request->places);
        $this->repository->save($booking);
        $this->sessionRepository->save($session);

        $this->eventDispatcher->dispatch(new BookingCreatedEvent($booking->getId()));

        return $booking;
    }
}
