<?php

declare(strict_types=1);

namespace App\Tests\Unit\BoundedContext\Booking\Application\UseCase;

use App\BoundedContext\Booking\Application\Request\BookingCreatorRequest;
use App\BoundedContext\Booking\Application\UseCase\BookingCreator;
use App\BoundedContext\Booking\Domain\Entity\Booking;
use App\BoundedContext\Booking\Domain\Event\BookingCreatedEvent;
use App\BoundedContext\Booking\Domain\Repository\BookingRepositoryInterface;
use App\BoundedContext\Session\Domain\Entity\Session;
use App\BoundedContext\Session\Domain\Repository\SessionRepositoryInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Uid\Uuid;
use InvalidArgumentException;
use LogicException;

final class BookingCreatorTest extends TestCase
{
    public function testShouldCreateBookingAndDispatchEventSuccessfully(): void
    {
        $sessionId = Uuid::v4();
        $bookingRepoMock = $this->createMock(BookingRepositoryInterface::class);
        $sessionRepoMock = $this->createMock(SessionRepositoryInterface::class);
        $eventDispatcherMock = $this->createMock(EventDispatcherInterface::class);

        $sessionMock = $this->createMock(Session::class);
        $sessionMock->method('getId')->willReturn($sessionId);
        $sessionMock->method('getFreePlaces')->willReturn(10);
        $sessionMock->method('getPrice')->willReturn(20.00);

        $sessionRepoMock->method('findById')->willReturn($sessionMock);

        $bookingRepoMock->expects($this->once())->method('save');
        $sessionRepoMock->expects($this->once())->method('save');

        $eventDispatcherMock->expects($this->once())
            ->method('dispatch')
            ->with($this->isInstanceOf(BookingCreatedEvent::class));

        $useCase = new BookingCreator($bookingRepoMock, $sessionRepoMock, $eventDispatcherMock);
        $bookingRequest = new BookingCreatorRequest($sessionId, Uuid::v4(), 4);
        $result = $useCase->execute($bookingRequest);

        $this->assertInstanceOf(Booking::class, $result);
        $this->assertSame(4, $result->getPlaces());
        $this->assertSame(80.00, $result->getTotalPrice());
    }

    public function testShouldThrowExceptionAndAbortWhenSessionIsFull(): void
    {
        $sessionId = Uuid::v4();
        $bookingRepoMock = $this->createMock(BookingRepositoryInterface::class);
        $sessionRepoMock = $this->createMock(SessionRepositoryInterface::class);
        $eventDispatcherMock = $this->createMock(EventDispatcherInterface::class);

        $sessionMock = $this->createMock(Session::class);
        $sessionRepoMock->method('findById')->willReturn($sessionMock);
        $sessionMock->method('getFreePlaces')->willReturn(2);

        $bookingRepoMock->expects($this->never())->method('save');
        $eventDispatcherMock->expects($this->never())->method('dispatch');

        $useCase = new BookingCreator($bookingRepoMock, $sessionRepoMock, $eventDispatcherMock);

        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Not enough places in this session');

        $bookingRequest = new BookingCreatorRequest($sessionId, Uuid::v4(), 100);
        $useCase->execute($bookingRequest);
    }

    public function testShouldThrowExceptionWhenSessionDoesNotExist(): void
    {
        $sessionId = Uuid::v4();
        $bookingRepoMock = $this->createMock(BookingRepositoryInterface::class);
        $sessionRepoMock = $this->createMock(SessionRepositoryInterface::class);
        $eventDispatcherMock = $this->createMock(EventDispatcherInterface::class);

        $sessionRepoMock->method('findById')->willReturn(null);

        $bookingRepoMock->expects($this->never())->method('save');
        $eventDispatcherMock->expects($this->never())->method('dispatch');

        $useCase = new BookingCreator($bookingRepoMock, $sessionRepoMock, $eventDispatcherMock);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Session not found');

        $bookingRequest = new BookingCreatorRequest($sessionId, Uuid::v4(), 100);
        $useCase->execute($bookingRequest);
    }
}
