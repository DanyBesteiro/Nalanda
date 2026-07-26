<?php

declare(strict_types=1);

namespace App\BoundedContext\Booking\Infrastructure\Persistence;

use App\BoundedContext\Booking\Domain\Entity\Booking;
use App\BoundedContext\Booking\Domain\Repository\BookingRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Uuid;

/**
 * @extends ServiceEntityRepository<Booking>
 */
class BookingRepository extends ServiceEntityRepository implements BookingRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Booking::class);
    }

    public function findById(Uuid $id): ?Booking
    {
        return $this->find($id);
    }

    public function save(Booking $booking): void
    {
        $this->getEntityManager()->persist($booking);
        $this->getEntityManager()->flush();
    }
}
