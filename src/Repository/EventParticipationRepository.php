<?php

namespace App\Repository;

use App\Entity\Event;
use App\Entity\EventParticipation;
use App\Entity\Team;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class EventParticipationRepository
 */
class EventParticipationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EventParticipation::class);
    }

    /**
     * @param Event $event
     * @param Team $team
     *
     * @return EventParticipation[]|null
     */
    public function findEventParticipations(Event $event, Team $team): ?array
    {
        $query = $this->createQueryBuilder('ep');
        $query->innerJoin('ep.event', 'e');
        $query->innerJoin('ep.teams', 't');
        $query->orderBy('ep.createdAt', 'DESC')
            ->where('e.id = :eventId')
            ->andWhere('t.id = :teamId')
            ->setParameter('eventId', $event->getId())
            ->setParameter('teamId', $team->getId());
        $lodging = $query->getQuery()->getResult();

        return $lodging;
    }
}
