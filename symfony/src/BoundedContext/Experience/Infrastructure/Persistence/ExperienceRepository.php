<?php

declare(strict_types=1);

namespace App\BoundedContext\Experience\Infrastructure\Persistence;

use App\BoundedContext\Experience\Domain\Entity\Experience;
use App\BoundedContext\Experience\Domain\Repository\ExperienceRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Uuid;

/**
 * @extends ServiceEntityRepository<Experience>
 */
class ExperienceRepository extends ServiceEntityRepository implements ExperienceRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Experience::class);
    }

    public function findById(Uuid $id): ?Experience
    {
        return $this->find($id);
    }

    public function save(Experience $experience): void
    {
        $this->getEntityManager()->persist($experience);
        $this->getEntityManager()->flush();
    }
}
