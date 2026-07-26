<?php

declare(strict_types=1);

namespace App\BoundedContext\Experience\Application\UseCase;

use App\BoundedContext\Experience\Application\Request\ExperienceCreatorRequest;
use App\BoundedContext\Experience\Domain\Entity\Experience;
use App\BoundedContext\Experience\Domain\Repository\ExperienceRepositoryInterface;
use Symfony\Component\Uid\Uuid;


class ExperienceCreator
{
    public function __construct(
        private readonly ExperienceRepositoryInterface $repository
    ) {}

    public function execute(ExperienceCreatorRequest $experienceRequest): Experience
    {
        $experience = new Experience(
            id: Uuid::v4(),
            title: $experienceRequest->title,
            description: $experienceRequest->description,
            providerId: $experienceRequest->providerId
        );

        $this->repository->save($experience);

        return $experience;
    }
}
