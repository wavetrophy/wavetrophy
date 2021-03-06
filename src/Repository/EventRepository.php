<?php

namespace App\Repository;

use App\Entity\Event;
use App\Entity\EventActivity;
use App\Entity\EventParticipation;
use App\Entity\Team;
use App\Entity\User;
use App\Entity\Wave;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class EventRepository
 */
class EventRepository extends ServiceEntityRepository
{
    /**
     * EventRepository constructor.
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    /**
     * @param User|null $user
     * @param Wave|null $currentWave
     *
     * @return array
     */
    public function getEventsForUser(?User $user, ?Wave $currentWave)
    {
        $query = $this->createQueryBuilder('e');
        $query->innerJoin('e.participations', 'p');
        $query->innerJoin('e.wave', 'w');
        $query->innerJoin('p.teams', 't');
        $query->innerJoin('t.users', 'u');
        $query->where('u.id = :id')->setParameter('id', $user->getId());
        $query->andWhere('w.id = :wave')->setParameter('wave', $currentWave ? $currentWave->getId() : null);
        $query->andWhere('e.deletedAt IS NULL');
        $query->andWhere('p.deletedAt IS NULL');
        $query->andWhere('u.deletedAt IS NULL');
        $result = $query->getQuery()->getResult();
        $events = [];
        /** @var Event $event */
        foreach ($result as $event) {
            $e = $this->formatEvent($event, $user);

            $events[$event->getStart()->format('Ymd_His')] = $e;
        }

        return $events;
    }

    /**
     * @param Wave $currentWave
     *
     * @return array
     */
    public function getEventsForWave(Wave $currentWave)
    {
        $query = $this->createQueryBuilder('e');
        $query->innerJoin('e.participations', 'p');
        $query->innerJoin('e.wave', 'w');
        $query->innerJoin('p.teams', 't');
        $query->innerJoin('t.users', 'u');
        $query->andWhere('w.id = :wave')->setParameter('wave', $currentWave ? $currentWave->getId() : null);
        $query->andWhere('e.deletedAt IS NULL');
        $query->andWhere('p.deletedAt IS NULL');
        $query->andWhere('u.deletedAt IS NULL');
        $result = $query->getQuery()->getResult();
        $events = [];
        /** @var Event $event */
        foreach ($result as $event) {
            $e = $this->formatEvent($event);

            $events[$event->getStart()->format('Ymd_His')] = $e;
        }

        return $events;
    }

    /**
     * @param Event|null $event
     * @param User|null  $user
     * @param Wave|null  $currentWave
     *
     * @return array|null
     */
    public function getEventForUser(?Event $event, ?User $user, ?Wave $currentWave)
    {
        $query = $this->createQueryBuilder('e');
        $query->innerJoin('e.participations', 'p');
        $query->innerJoin('e.wave', 'w');
        $query->innerJoin('p.teams', 't');
        $query->innerJoin('t.users', 'u');
        $query->where('u.id = :id')->setParameter('id', $user->getId());
        $query->andWhere('w.id = :wave')->setParameter('wave', $currentWave->getId());
        $query->andWhere('e.id = :event')->setParameter('event', $event->getId());
        $query->andWhere('e.deletedAt IS NULL');
        $query->andWhere('p.deletedAt IS NULL');
        $query->andWhere('u.deletedAt IS NULL');
        $result = $query->getQuery()->getResult();
        if (empty($result)) {
            return $this->formatEvent($event);
        }

        return $this->formatEvent($result[0], $user);
    }

    /**
     * @param User  $user
     * @param Event $event
     *
     * @return EventParticipation|null
     */
    public function getParticipationForUserByEvent(User $user, Event $event): ?EventParticipation
    {
        $query = $this->getEntityManager()
            ->getRepository(EventParticipation::class)
            ->createQueryBuilder('p');
        $query->innerJoin('p.teams', 't');
        $query->innerJoin('p.event', 'e');
        $query->innerJoin('t.users', 'u');
        $query->where('u.id = :userId')->setParameter('userId', $user->getId());
        $query->andWhere('e.id = :eventId')->setParameter('eventId', $event->getId());
        $query->andWhere('p.deletedAt IS NULL');

        $result = $query->getQuery()->getResult();

        return !empty($result) ? $result[0] : null;
    }

    /**
     * @param Event $event
     *
     * @return array
     */
    public function getTeamsDataForEvent(Event $event)
    {
        $teams = [];
        $wave = $event->getWave();
        if (empty($wave)) {
            $wave = $this->getEntityManager()->getRepository(Wave::class)->getCurrentWave();
        }
        $teamEntites = $this->_em->getRepository(Team::class)->getWaveTeams($wave ? $wave->getId() : null);
        foreach ($teamEntites as $team) {
            $teams[$team->getId()] = [
                'id' => $team->getId(),
                'name' => $team->getName() . '(' . $team->getStartNumber() . ')',
                'enabled' => false,
                'arrival' => null,
                'departure' => null,
            ];
        }

        $participations = $event->getParticipations();
        if ($participations) {
            /** @var EventParticipation $participation */
            foreach ($participations->getValues() as $participation) {
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
        }

        return $teams;
    }

    /**
     * @param User|null $user
     * @param Event     $event
     *
     * @return array
     */
    protected function formatEvent(?Event $event, ?User $user = null): array
    {
        if ($user) {
            $personalParticipation = $this->getParticipationForUserByEvent($user, $event);
        } else {
            $personalParticipation = new EventParticipation($event->getStart(), $event->getEnd(), $event);
        }

        $start = $event->getStartAsMoment()->format('Y-m-d[T]H:i:s.0000[Z]');
        $end = $event->getEndAsMoment()->format('Y-m-d[T]H:i:s.0000[Z]');
        $activities = [];

        /** @var EventActivity $eventActivity */
        foreach ($event->getEventActivities()->getValues() as $eventActivity) {
            if ($eventActivity->isDeleted()) {
                continue;
            }
            $activities[] = [
                'id' => $eventActivity->getId(),
                'title' => $eventActivity->getTitle(),
                'description' => $eventActivity->getDescription(),
                'start' => $eventActivity->getStartAsMoment()->format('Y-m-d[T]H:i:s.0000[Z]'),
                'end' => $eventActivity->getEndAsMoment()->format('Y-m-d[T]H:i:s.0000[Z]'),
            ];
        }

        $e = [
            'id' => $event->getId(),
            'name' => $event->getName(),
            'start' => $start,
            'end' => $end,
            'lat' => $event->getLat(),
            'lon' => $event->getLon(),
            'location' => $event->getLocation(),
            'thumbnail' => '/media/thumbnails/events/' . $event->getThumbnail(),
            'personal_participation' => $this->formatParticipation($personalParticipation),
            'participations' => [],
            'activities' => $activities,
        ];

        /** @var EventParticipation $participation */
        foreach ($event->getParticipations()->getValues() as $participation) {
            $p = $this->formatParticipation($participation);

            $e['participations'][] = $p;
        }

        return $e;
    }

    /**
     * @param EventParticipation $participation
     *
     * @return array
     */
    protected function formatParticipation(?EventParticipation $participation): ?array
    {
        if (empty($participation)) {
            return null;
        }
        $p = [
            'id' => $participation->getId(),
            'arrival' => $participation->getArrivalAsMoment()->format('Y-m-d[T]H:i:s.0000[Z]'),
            'departure' => $participation->getDepartureAsMoment()->format('Y-m-d[T]H:i:s.0000[Z]'),
            'teams' => [],
        ];

        /** @var Team $team */
        foreach ($participation->getTeams()->getValues() as $team) {
            $p['teams'][] = [
                'id' => $team->getId(),
                'name' => $team->getName(),
                'start_number' => $team->getStartNumber(),
            ];
        }

        return $p;
    }
}
