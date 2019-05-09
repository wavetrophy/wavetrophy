<?php

namespace App\Repository;

use App\Entity\Event;
use App\Entity\Location;
use App\Entity\Team;
use App\Entity\TeamParticipation;
use App\Entity\User;
use App\Exception\ResourceNotFoundException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use InvalidArgumentException;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class LocationRepository
 */
class LocationRepository extends ServiceEntityRepository
{
    /**
     * UserRepository constructor.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Location::class);
    }

    /**
     * @param string $userId
     *
     * @return array
     * @throws ResourceNotFoundException
     */
    public function getLocationsForUser(string $userId)
    {
        /** @var User $user */
        $user = $this->_em->find(User::class, $userId);
        if (empty($user)) {
            throw new ResourceNotFoundException('User not found');
        }

        $teamId = null;
        if ($team = $user->getTeam()) {
            $teamId = $team->getId();
        } else {
            throw new InvalidArgumentException('User must be in a team');
        }

        $locations = $this->createQueryBuilder('l')
            ->select('l')
            ->join('l.events', 'e')
            ->orderBy('e.start', 'ASC')
            ->addOrderBy('l.name', 'ASC')
            ->getQuery()
            ->getResult();

        $result = [];
        /** @var Location $location */
        foreach ($locations as $location) {
            $loc = [
                'id' => $location->getId(),
                'name' => $location->getName(),
                'thumbnail' => $location->getThumbnail(),
                'lat' => $location->getLat(),
                'lon' => $location->getLon(),
                'events' => null,
                'arrival' => null,
                'departure' => null,
                'must_participate' => false,
            ];
            $events = [];
            /** @var Event $event */
            foreach ($location->getEvents() as $event) {
                $events[] = [
                    'id' => $event->getId(),
                    'name' => $event->getName(),
                    'description' => $event->getDescription(),
                    'start' => $event->getStart()->format('Y-m-d H:i:s'),
                    'end' => $event->getEnd()->format('Y-m-d H:i:s'),
                ];
            }

            $loc['events'] = $events;
            $loc['arrival'] = $events[0]['start'];
            $loc['departure'] = end($events)['end'];

            $teamParticipations = $location->getTeamParticipations()->getValues();
            $hasTeamParticipation = false;
            /** @var TeamParticipation $teamParticipation */
            foreach ($teamParticipations as $teamParticipation) {
                $teams = $teamParticipation->getTeams()->getValues();
                /** @var Team $team */
                foreach ($teams as $team) {
                    if ($team->getId() === $teamId) {
                        $hasTeamParticipation = true;
                    }
                }
                if ($hasTeamParticipation) {
                    $loc['arrival'] = $teamParticipation->getArrival()->format('Y-m-d H:i:s') ?: $loc['arrival'];
                    $loc['departure'] = $teamParticipation->getDeparture()->format('Y-m-d H:i:s') ?: $loc['departure'];
                    $loc['must_participate'] = true;
                }
            }
            $result[] = $loc;
        }

        return $result;
    }

    /**
     * @param $locationId
     * @param $userId
     *
     * @throws ResourceNotFoundException
     */
    public function getLocation($locationId, $userId)
    {
        /** @var User $user */
        $user = $this->_em->find(User::class, $userId);
        if (empty($user)) {
            throw new ResourceNotFoundException('User not found');
        }

        $teamId = null;
        if ($team = $user->getTeam()) {
            $teamId = $team->getId();
        } else {
            throw new InvalidArgumentException('User must be in a team');
        }

        $location = $this->createQueryBuilder('l')
            ->select('l')
            ->join('l.events', 'e')
            ->orderBy('e.start', 'ASC')
            ->addOrderBy('l.name', 'ASC')
            ->where('l.id = :id')
            ->setParameter('id', $locationId)
            ->getQuery()
            ->getResult();

        if (!$location) {
            throw new ResourceNotFoundException('Location not found');
        }
        $location = $location[0];

        $loc = [
            'id' => $location->getId(),
            'name' => $location->getName(),
            'thumbnail' => $location->getThumbnail(),
            'lat' => $location->getLat(),
            'lon' => $location->getLon(),
            'events' => null,
            'arrival' => null,
            'departure' => null,
            'must_participate' => false,
        ];
        $events = [];
        /** @var Event $event */
        foreach ($location->getEvents() as $event) {
            $events[] = [
                'id' => $event->getId(),
                'name' => $event->getName(),
                'description' => $event->getDescription(),
                'start' => $event->getStart()->format('Y-m-d H:i:s'),
                'end' => $event->getEnd()->format('Y-m-d H:i:s'),
            ];
        }

        $loc['events'] = $events;
        $loc['arrival'] = $events[0]['start'];
        $loc['departure'] = end($events)['end'];

        $teamParticipations = $location->getTeamParticipations()->getValues();
        $hasTeamParticipation = false;
        /** @var TeamParticipation $teamParticipation */
        foreach ($teamParticipations as $teamParticipation) {
            $teams = $teamParticipation->getTeams()->getValues();
            /** @var Team $team */
            foreach ($teams as $team) {
                if ($team->getId() === $teamId) {
                    $hasTeamParticipation = true;
                }
            }
            if ($hasTeamParticipation) {
                $loc['arrival'] = $teamParticipation->getArrival()->format('Y-m-d H:i:s') ?: $loc['arrival'];
                $loc['departure'] = $teamParticipation->getDeparture()->format('Y-m-d H:i:s') ?: $loc['departure'];
                $loc['must_participate'] = true;
            }
        }
        return $loc;
    }
}
