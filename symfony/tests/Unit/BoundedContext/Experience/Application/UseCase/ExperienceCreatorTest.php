<?php

declare(strict_types=1);

namespace App\Tests\Unit\BoundedContext\Experience\Application\UseCase;

use App\BoundedContext\Experience\Application\Request\ExperienceCreatorRequest;
use App\BoundedContext\Experience\Application\UseCase\ExperienceCreator;
use App\BoundedContext\Experience\Domain\Entity\Experience;
use App\BoundedContext\Experience\Domain\Repository\ExperienceRepositoryInterface;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

final class ExperienceCreatorTest extends TestCase
{
    public function testCreateExperienceProperly(): void
    {
        $repositoryMock = $this->createMock(ExperienceRepositoryInterface::class);

        $repositoryMock->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf(Experience::class));

        $useCase = new ExperienceCreator($repositoryMock);

        $request = new ExperienceCreatorRequest(
            'Text experience',
            'Description of the experience',
            Uuid::v4()
        );
        $useCase->execute($request);
    }

    public function testShouldNotCreateExperienceWhenTitleIsEmpty(): void
    {
        $repositoryMock = $this->createMock(ExperienceRepositoryInterface::class);
        $repositoryMock->expects($this->never())
            ->method('save');

        $useCase = new ExperienceCreator($repositoryMock);

        $request = new ExperienceCreatorRequest(
            '',
            'Description of the experience',
            Uuid::v4()
        );

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Not valid title');

        $useCase->execute($request);
    }

    public function testShouldNotCreateExperienceWhenTitleContainsOnlySpaces(): void
    {
        $repositoryMock = $this->createMock(ExperienceRepositoryInterface::class);

        $repositoryMock->expects($this->never())
            ->method('save');

        $useCase = new ExperienceCreator($repositoryMock);

        $request = new ExperienceCreatorRequest(
            '     ',
            'Description of the experience',
            Uuid::v4()
        );

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Not valid title');

        $useCase->execute($request);
    }
}
