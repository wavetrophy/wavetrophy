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
     * @param int | null $waveId
     *
     * @return Team[] | null
     */
    public function getWaveTeams(?int $waveId)
    {
        $query = $this->createQueryBuilder('t');
        $query->innerJoin('t.group', 'g');
        $query->innerJoin('g.wave', 'w');
        $query->where('w.id = :id')->setParameter('id', $waveId)
            ->andWhere('t.deletedAt IS NULL');
        $result = $query->getQuery()->getResult();
        return $result;
    }

    /**
     * Get a team by its start number for a wave
     *
     * @param int $waveId
     * @param int $teamNr
     *
     * @return Team|null
     */
    public function getTeamForWaveByStartNumber(int $waveId, int $teamNr): ?Team
    {
        $query = $this->createQueryBuilder('t');
        $query->innerJoin('t.group', 'g');
        $query->innerJoin('g.wave', 'w');
        $query->where('w.id = :id')->setParameter('id', $waveId)
            ->andWhere('t.startNumber = :startNr')->setParameter('startNr', $teamNr)
            ->andWhere('t.deletedAt IS NULL')
            ->orderBy('t.createdAt', 'DESC');

        $result = $query->getQuery()->getResult();
        return $result ? $result[0] : null;
    }
}
