<?php

declare(strict_types=1);

namespace App\BoundedContext\Session\Application\DTO;

use App\BoundedContext\Session\Domain\Entity\Session;
use DateTimeImmutable;
use Symfony\Component\Uid\Uuid;

class SessionDTO
{
    public function __construct(
        Uuid $id,
        Uuid $experienceId,
        DateTimeImmutable $date,
        int $maxCapacity,
        float $price
    ) {}

    /**
     * @return array{
     * id: string,
     * experienceId: string,
     * date: string,
     * maxCapacity: int,
     * price: float
     * }
     */
    public static function fromEntity(Session $session): array
    {
        return [
            'id' => $session->getId()->toRfc4122(),
            'experienceId' => $session->getExperienceId()->toRfc4122(),
            'date' => $session->getDate()->format('Y-m-d H:i:s'),
            'maxCapacity' => $session->getMaxCapacity(),
            'price' => $session->getPrice(),
        ];
    }
}
