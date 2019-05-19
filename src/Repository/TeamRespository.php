<?php

namespace App\Repository;

use App\Entity\Team;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class TeamRespository
 */
class TeamRespository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Team::class);
    }

    /**
     * @param int $waveId
     *
     * @return Team[] | null
     */
    public function getWaveTeams(int $waveId)
    {
        $query = $this->createQueryBuilder('t');
        $query->innerJoin('t.group', 'g');
        $query->innerJoin('g.wave', 'w');
        $query->where('w.id = :id')->setParameter('id', $waveId);
        $result = $query->getQuery()->getResult();
        return $result;
    }
}
