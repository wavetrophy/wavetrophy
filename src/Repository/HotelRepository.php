<?php

namespace App\Repository;

use App\Entity\Hotel;
use App\Entity\Lodging;
use App\Entity\Team;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class HotelRepository
 */
class HotelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Hotel::class);
    }

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
}
