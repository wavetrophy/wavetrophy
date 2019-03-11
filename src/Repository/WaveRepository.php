<?php

namespace App\Repository;

use App\Entity\Wave;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class WaveRepository
 */
class WaveRepository extends ServiceEntityRepository
{
    /**
     * UserRepository constructor.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Wave::class);
    }

    /**
     * Get the current wave.
     *
     * @return int
     */
    public function getCurrentWave()
    {
        $result = $this->createQueryBuilder('w')
            ->select('w.id')
            ->orderBy('w.start')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
        return $result ? $result['id'] : null;
    }
}
