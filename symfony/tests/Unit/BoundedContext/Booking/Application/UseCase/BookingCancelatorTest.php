<?php

declare(strict_types=1);

namespace App\Tests\Unit\BoundedContext\Booking\Application\UseCase;

use App\BoundedContext\Booking\Application\UseCase\BookingCancelator;
use App\BoundedContext\Booking\Application\UseCase\BookingCanceler;
use App\BoundedContext\Booking\Domain\Entity\Booking;
use App\BoundedContext\Booking\Domain\Event\BookingCanceledEvent;
use App\BoundedContext\Booking\Domain\Repository\BookingRepositoryInterface;
use App\BoundedContext\Booking\Domain\ValueObject\BookingStatus;
use App\BoundedContext\Session\Domain\Entity\Session;
use App\BoundedContext\Session\Domain\Repository\SessionRepositoryInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Uid\Uuid;
use InvalidArgumentException;

use function Symfony\Component\Clock\now;

final class BookingCancelatorTest extends TestCase
{
    public function testShouldCancelBookingAndReleasePlacesSuccessfully(): void
    {
        $bookingRepoMock = $this->createMock(BookingRepositoryInterface::class);
        $sessionRepoMock = $this->createMock(SessionRepositoryInterface::class);
        $eventDispatcherMock = $this->createMock(EventDispatcherInterface::class);

        $bookingId = Uuid::v4();
        $sessionId = Uuid::v4();

        $bookingMock = $this->createMock(Booking::class);
        $bookingMock->method('getId')->willReturn($bookingId);
        $bookingMock->method('getSessionId')->willReturn($sessionId);
        $bookingMock->method('getPlaces')->willReturn(4);

        $sessionMock = $this->createMock(Session::class);
        $sessionMock->method('getDate')->willReturn(now()->modify('+ 10 days'));

        $bookingRepoMock->method('findById')
            ->with($this->callback(function (Uuid $id) use ($bookingId) {
                return $id->toRfc4122() === $bookingId->toRfc4122();
            }))
            ->willReturn($bookingMock);

        $sessionRepoMock->method('findById')
            ->with($this->callback(function (Uuid $id) use ($sessionId) {
                return $id->toRfc4122() === $sessionId->toRfc4122();
            }))
            ->willReturn($sessionMock);

        $sessionMock->expects($this->once())->method('unReservePlaces')->with(4);
        $bookingMock->expects($this->once())->method('cancel');

        $bookingRepoMock->expects($this->once())->method('save')->with($bookingMock);
        $sessionRepoMock->expects($this->once())->method('save')->with($sessionMock);

        $eventDispatcherMock->expects($this->once())
            ->method('dispatch')
            ->with($this->isInstanceOf(BookingCanceledEvent::class));

        $useCase = new BookingCancelator($bookingRepoMock, $sessionRepoMock, $eventDispatcherMock);
        $useCase->execute($bookingId->toRfc4122());
    }
}
