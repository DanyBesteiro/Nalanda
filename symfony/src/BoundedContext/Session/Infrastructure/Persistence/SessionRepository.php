<?php

declare(strict_types=1);

namespace App\BoundedContext\Session\Infrastructure\Persistence;

use App\BoundedContext\Session\Domain\Entity\Session;
use App\BoundedContext\Session\Domain\Repository\SessionRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Uuid;

/**
 * @extends ServiceEntityRepository<Session>
 */
class SessionRepository extends ServiceEntityRepository implements SessionRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Session::class);
    }

    public function findById(Uuid $id): ?Session
    {
        return $this->find($id);
    }

    public function save(Session $session): void
    {
        $this->getEntityManager()->persist($session);
        $this->getEntityManager()->flush();
    }
}
