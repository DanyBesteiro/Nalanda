<?php

declare(strict_types=1);

namespace App\BoundedContext\Session\Domain\Entity;

use Doctrine\ORM\Mapping as ORM;
use DateTimeImmutable;
use InvalidArgumentException;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
#[ORM\Table(name: 'session')]
class Session
{
    #[ORM\Id]
    #[ORM\Column(type: 'guid')]
    private Uuid $id;

    #[ORM\Column(type: 'guid', name: 'experience_id')]
    private Uuid $experienceId;

    #[ORM\Column(type: 'datetime_immutable', name: 'session_date')]
    private DateTimeImmutable $date;

    #[ORM\Column(type: 'integer', name: 'max_capacity')]
    private int $maxCapacity;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private float $price;

    #[ORM\Column(type: 'integer', name: 'reserved_places')]
    private int $reservedPlaces;

    public function __construct(
        Uuid $experienceId,
        DateTimeImmutable $date,
        int $maxCapacity,
        float $price
    ) {
        $this->id = Uuid::v4();

        $this->experienceId = $experienceId;
        $this->date = $date;
        $this->maxCapacity = $maxCapacity;
        $this->price = $price;
        $this->reservedPlaces = 0;

        if ($this->maxCapacity <= 0) {
            throw new InvalidArgumentException('Capacity must be greater than zero.');
        }

        if ($this->price < 0) {
            throw new InvalidArgumentException('Price cannot be negative.');
        }
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getExperienceId(): Uuid
    {
        return $this->experienceId;
    }

    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }

    public function getMaxCapacity(): int
    {
        return $this->maxCapacity;
    }

    public function getPrice(): float
    {
        return (float) $this->price;
    }

    public function getReservedPlaces(): int
    {
        return $this->reservedPlaces;
    }
}
