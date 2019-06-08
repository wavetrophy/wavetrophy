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
            ->setParameter('waveId', $waveId)
            ->andWhere('g.deletedAt IS NULL');
        $result = $query->getQuery()->getResult();
        return $result;
    }

    /**
     * Find group by name for a specific wave (can also be used with group numbers if the groups are named like "Gruppe
     * 1")
     *
     * @param int $waveId
     * @param string $name
     *
     * @return Group|null
     */
    public function findByNameForWave(int $waveId, string $name): ?Group
    {
        $query = $this->createQueryBuilder('g');
        $query->innerJoin('g.wave', 'w')
            ->where('w.id = :waveId')
            ->andWhere('g.name LIKE :group')
            ->setParameter('waveId', $waveId)
            ->setParameter('group', '%' . $name . '%')
            ->andWhere('g.deletedAt IS NULL');
        $result = $query->getQuery()->getResult();
        return $result ? $result[0] : null;
    }
}
