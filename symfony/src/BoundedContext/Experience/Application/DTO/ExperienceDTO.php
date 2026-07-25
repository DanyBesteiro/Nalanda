<?php

declare(strict_types=1);

namespace App\BoundedContext\Experience\Application\DTO;

use App\BoundedContext\Experience\Domain\Aggregate\Experience;
use Symfony\Component\Uid\Uuid;

class ExperienceDTO
{
    public function __construct(
        public Uuid $id,
        public string $name,
        public string $description,
        public Uuid $providerId
    ) {}

    /**
     * @return array{
     * id: string,
     * name: string,
     * description: string,
     * providerId: string
     * }
     */
    public static function fromEntity(Experience $experience): array
    {
        return [
            'id' => $experience->getId()->toRfc4122(),
            'title' => $experience->getTitle(),
            'description' => $experience->getDescription(),
            'providerId' => $experience->getProviderId()->toRfc4122(),
        ];
    }
}
