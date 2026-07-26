<?php

declare(strict_types=1);

namespace App\BoundedContext\Session\Application\UseCase;

use App\BoundedContext\Session\Application\Request\SessionCreatorRequest;
use App\BoundedContext\Session\Domain\Entity\Session;
use App\BoundedContext\Session\Domain\Repository\SessionRepositoryInterface;
use App\BoundedContext\Session\Domain\Service\ExperienceCheckerInterface;
use App\BoundedContext\Session\Domain\Service\ExperienceSessionInDateCheckerInterface;
use DateTimeImmutable;
use InvalidArgumentException;
use LogicException;

final class SessionCreator
{
    public function __construct(
        private readonly ExperienceCheckerInterface $experienceChecker,
        private readonly ExperienceSessionInDateCheckerInterface $sessionInDateChecker,
        private readonly SessionRepositoryInterface $sessionRepository
    ) {}

    public function execute(SessionCreatorRequest $request): Session
    {
        $date = new DateTimeImmutable($request->date);

        if (!$this->experienceChecker->exists($request->experienceId)) {
            throw new InvalidArgumentException('Experience not found');
        }

        if ($this->sessionInDateChecker->existsSessionInDate(
            experienceId: $request->experienceId,
            date: $date
        )) {
            throw new LogicException('Experience with Session-in-date already');
        }

        $session = new Session(
            experienceId: $request->experienceId,
            date: $date,
            maxCapacity: $request->maxCapacity,
            price: $request->price
        );

        $this->sessionRepository->save($session);

        return $session;
    }
}
