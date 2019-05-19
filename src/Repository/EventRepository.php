<?php

namespace App\Repository;

use App\Entity\Event;
use App\Entity\EventParticipation;
use App\Entity\Team;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class EventRepository
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    public function getTeamsDataForEvent(Event $event)
    {
        $teams = [];
        $teamEntites = $this->_em->getRepository(Team::class)->getWaveTeams($event->getWave()->getId());
        foreach ($teamEntites as $team) {
            $teams[$team->getId()] = [
                'id' => $team->getId(),
                'name' => $team->getName() . '(' . $team->getStartNumber() . ')',
                'enabled' => false,
                'arrival' => null,
                'departure' => null,
            ];
        }

        /** @var EventParticipation[] $participations */
        $participations = $event->getParticipations()->getValues();
        foreach ($participations as $participation) {
            foreach ($participation->getTeams()->getValues() as $team) {
                $teams[$team->getId()] = [
                    'id' => $team->getId(),
                    'name' => $team->getName() . '(' . $team->getStartNumber() . ')',
                    'enabled' => true,
                    'arrival' => $participation->getArrival()->format('H:i'),
                    'departure' => $participation->getDeparture()->format('H:i'),
                ];
            }
        }

        return $teams;
    }
}
