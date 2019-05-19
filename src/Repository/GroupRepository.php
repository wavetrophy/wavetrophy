<?php

namespace App\Repository;

use App\Entity\Group;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class GroupRepository
 */
class GroupRepository extends ServiceEntityRepository
{
    /**
     * GroupRepository constructor.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Group::class);
    }
    /**
     * @param int $waveId
     *
     * @return Group[]
     */
    public function getByWave(?int $waveId)
    {
        $query = $this->createQueryBuilder('g');
        $query->innerJoin('g.wave', 'w')
            ->where('w.id = :waveId')
            ->setParameter('waveId', $waveId);
        $result = $query->getQuery()->getResult();
        return $result;
    }
}
