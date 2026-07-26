<?php

declare(strict_types=1);

namespace App\Tests\Unit\BoundedContext\Session\Application\UseCase;

use App\BoundedContext\Session\Application\Request\SessionCreatorRequest;
use App\BoundedContext\Session\Application\UseCase\SessionCreator;
use App\BoundedContext\Session\Domain\Entity\Session;
use App\BoundedContext\Session\Domain\Repository\SessionRepositoryInterface;
use App\BoundedContext\Session\Domain\Service\ExperienceCheckerInterface;
use App\BoundedContext\Session\Domain\Service\ExperienceSessionInDateCheckerInterface;
use App\BoundedContext\Session\Domain\Service\SessionDateValidator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;
use InvalidArgumentException;
use LogicException;
use DateTimeImmutable;

final class SessionCreatorTest extends TestCase
{
    private const FUTURE_DATE = '2029-10-15 12:00:00';
    private const PAST_DATE = '2020-01-01 12:00:00';

    public function testShouldCreateSessionSuccessfully(): void
    {
        $sessionRepoMock = $this->createMock(SessionRepositoryInterface::class);
        $experienceCheckerMock = $this->createMock(ExperienceCheckerInterface::class);
        $experienceDateCheckerMock = $this->createMock(ExperienceSessionInDateCheckerInterface::class);

        $experienceCheckerMock->method('exists')->willReturn(true);
        $sessionRepoMock->method('findByExperienceAndDate')->willReturn(null);

        $sessionRepoMock->expects($this->once())->method('save');

        $useCase = new SessionCreator($experienceCheckerMock, $experienceDateCheckerMock, $sessionRepoMock);

        $request = new SessionCreatorRequest(Uuid::v4(), self::FUTURE_DATE, 50, 25.00);
        $result = $useCase->execute($request);

        $this->assertInstanceOf(Session::class, $result);
        $this->assertSame(50, $result->getMaxCapacity());
        $this->assertSame(25.00, $result->getPrice());
        $this->assertSame(0, $result->getReservedPlaces());
    }
    public function testShouldThrowExceptionWhenExperienceDoesNotExist(): void
    {
        $sessionRepoMock = $this->createMock(SessionRepositoryInterface::class);
        $experienceCheckerMock = $this->createMock(ExperienceCheckerInterface::class);
        $experienceDateCheckerMock = $this->createMock(ExperienceSessionInDateCheckerInterface::class);

        $experienceCheckerMock->method('exists')->willReturn(false);
        $sessionRepoMock->expects($this->never())->method('save');

        $useCase = new SessionCreator($experienceCheckerMock, $experienceDateCheckerMock, $sessionRepoMock);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Experience not found');

        $request = new SessionCreatorRequest(Uuid::v4(), self::FUTURE_DATE, 50, 25.00);
        $useCase->execute($request);
    }

    public function testShouldThrowExceptionWhenSessionAlreadyExistsOnSameDay(): void
    {
        $sessionRepoMock = $this->createMock(SessionRepositoryInterface::class);
        $experienceCheckerMock = $this->createMock(ExperienceCheckerInterface::class);
        $experienceDateCheckerMock = $this->createMock(ExperienceSessionInDateCheckerInterface::class);

        $experienceCheckerMock->method('exists')->willReturn(true);

        $existingSessionMock = $this->createMock(Session::class);
        $sessionRepoMock->method('findByExperienceAndDate')->willReturn($existingSessionMock);
        $experienceDateCheckerMock->method('existsSessionInDate')->willReturn(true);

        $sessionRepoMock->expects($this->never())->method('save');

        $useCase = new SessionCreator($experienceCheckerMock, $experienceDateCheckerMock, $sessionRepoMock);

        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Experience with Session-in-date already');

        $request = new SessionCreatorRequest(Uuid::v4(), self::FUTURE_DATE, 50, 25.00);
        $useCase->execute($request);
    }

    public function testShouldThrowExceptionWhenDateIsPast(): void
    {
        $sessionRepoMock = $this->createMock(SessionRepositoryInterface::class);
        $experienceCheckerMock = $this->createMock(ExperienceCheckerInterface::class);
        $experienceDateCheckerMock = $this->createMock(ExperienceSessionInDateCheckerInterface::class);

        $experienceCheckerMock->method('exists')->willReturn(true);
        $sessionRepoMock->method('findByExperienceAndDate')->willReturn(null);
        $sessionRepoMock->expects($this->never())->method('save');

        $useCase = new SessionCreator($experienceCheckerMock, $experienceDateCheckerMock, $sessionRepoMock);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Session date cannot be in the past.');

        $request = new SessionCreatorRequest(Uuid::v4(), self::PAST_DATE, 50, 25.00);
        $useCase->execute($request);
    }

    public function testShouldThrowExceptionWhenMaxCapacityIsInvalid(): void
    {
        $sessionRepoMock = $this->createMock(SessionRepositoryInterface::class);
        $experienceCheckerMock = $this->createMock(ExperienceCheckerInterface::class);
        $experienceDateCheckerMock = $this->createMock(ExperienceSessionInDateCheckerInterface::class);

        $experienceCheckerMock->method('exists')->willReturn(true);
        $sessionRepoMock->method('findByExperienceAndDate')->willReturn(null);
        $sessionRepoMock->expects($this->never())->method('save');

        $useCase = new SessionCreator($experienceCheckerMock, $experienceDateCheckerMock, $sessionRepoMock);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Capacity must be greater than zero.');

        $request = new SessionCreatorRequest(Uuid::v4(), self::FUTURE_DATE, 0, 25.00);
        $useCase->execute($request);
    }
}
