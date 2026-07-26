<?php

declare(strict_types=1);

namespace App\BoundedContext\Booking\Application\UseCase;

use App\BoundedContext\Booking\Application\Request\BookingCreatorRequest;
use App\BoundedContext\Booking\Domain\Entity\Booking;
use App\BoundedContext\Booking\Domain\Repository\BookingRepositoryInterface;
use App\BoundedContext\Session\Domain\Repository\SessionRepositoryInterface;
use InvalidArgumentException;
use LogicException;

use function PHPUnit\Framework\isNull;

class BookingCreator
{
    public function __construct(
        private readonly BookingRepositoryInterface $repository,
        private readonly SessionRepositoryInterface $sessionRepository
    ) {}

    public function execute(BookingCreatorRequest $request): Booking
    {
        $session = $this->sessionRepository->findById($request->sessionId);

        if (!$session) {
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

        $this->repository->save($booking);

        return $booking;
    }
}
