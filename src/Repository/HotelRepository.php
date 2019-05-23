<?php

namespace App\Repository;

use App\Entity\Hotel;
use App\Entity\Lodging;
use App\Entity\Team;
use App\Entity\User;
use App\Entity\Wave;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class HotelRepository
 */
class HotelRepository extends ServiceEntityRepository
{
    /**
     * HotelRepository constructor.
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Hotel::class);
    }

    /**
     * @param Hotel $hotel
     *
     * @return array
     */
    public function getTeamsDataForHotel(Hotel $hotel)
    {
        $teams = [];
        $teamEntites = $this->_em->getRepository(Team::class)->getWaveTeams($hotel->getWave()->getId());
        foreach ($teamEntites as $team) {
            $teams[$team->getId()] = [
                'id' => $team->getId(),
                'name' => $team->getName() . '(' . $team->getStartNumber() . ')',
                'enabled' => false,
                'comment' => null,
            ];
        }

        /** @var Lodging[] $lodgings */
        $lodgings = $hotel->getLodgings()->getValues();
        foreach ($lodgings as $lodging) {
            /** @var User[] $users */
            $users = $lodging->getUsers()->getValues();
            foreach ($users as $user) {
                if (empty($user->getTeam())) {
                    continue;
                }
                $teams[$user->getTeam()->getId()] = [
                    'id' => $user->getTeam()->getId(),
                    'name' => $user->getTeam()->getName() . '(' . $user->getTeam()->getStartNumber() . ')',
                    'enabled' => true,
                    'comment' => $lodging->getComment(),
                ];
            }
        }

        return $teams;
    }

    /**
     * @param User $user
     * @param Wave $currentWave
     */
    public function getHotelsForUser(User $user, Wave $currentWave)
    {
        $query = $this->createQueryBuilder('h');
        $query->innerJoin('h.lodgings', 'l');
        $query->innerJoin('l.users', 'u');
        $query->innerJoin('h.wave', 'w');
        $query->where('w.id = :waveId')->setParameter('waveId', $currentWave->getId());
        $query->andWhere('u.id = :userId')->setParameter('userId', $user->getId());
        $result = $query->getQuery()->getResult();

        $hotels = [];
        /** @var Hotel $hotel */
        foreach ($result as $hotel) {
            $h = [
                'id' => $hotel->getId(),
                'name' => $hotel->getName(),
                'thumbnail' => $hotel->getThumbnail(),
                'lat' => $hotel->getLat(),
                'lon' => $hotel->getLon(),
                'location' => $hotel->getLocation(),
                'check_in' => $hotel->getCheckInAsMoment()->format('Y-m-d[T]H:i:s.0000[Z]'),
                'check_out' => $hotel->getCheckOutAsMoment()->format('Y-m-d[T]H:i:s.0000[Z]'),
                'personal_lodging' => $this->formatLodging($this->getLodgingForUserByHotel($hotel, $user)),
            ];
            $h['lodgings'] = [];
            /** @var Lodging $lodging */
            foreach ($hotel->getLodgings()->getValues() as $lodging) {
                $h['lodgings'][] = $this->formatLodging($lodging);
            }
            $hotels[$hotel->getCheckIn()->format('ymd_his')] = $h;
        }

        return $hotels;
    }

    /**
     * @param Hotel $hotel
     * @param User $user
     *
     * @return Lodging|null
     */
    public function getLodgingForUserByHotel(Hotel $hotel, User $user): ?Lodging
    {
        $query = $this->getEntityManager()->getRepository(Lodging::class)->createQueryBuilder('l');
        $query->innerJoin('l.hotel', 'h');
        $query->innerJoin('l.users', 'u');
        $query->where('u.id = :userId')->setParameter('userId', $user->getId());
        $query->andWhere('h.id = :hotelId')->setParameter('hotelId', $hotel->getId());

        $result = $query->getQuery()->getResult();
        return !empty($result) ? $result[0] : null;
    }

    /**
     * @param Lodging $lodging
     *
     * @return array
     */
    protected function formatLodging(?Lodging $lodging): ?array
    {
        if (empty($lodging)) {
            return null;
        }

        $l = [
            'id' => $lodging->getId(),
            'comment' => $lodging->getComment(),
            'users' => [],
        ];

        /** @var User $user */
        foreach ($lodging->getUsers()->getValues() as $user) {
            $l['users'][] = [
                'id' => $user->getId(),
                'first_name' => $user->getFirstName(),
                'last_name' => $user->getLastName(),
                'username' => $user->getUsername(),
                'profile_picture' => $user->getProfilePicture()->asArray(),
            ];
        }
        return $l;
    }
}
