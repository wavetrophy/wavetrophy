<?php

namespace App\Repository;

use App\Entity\Hotel;
use App\Entity\Lodging;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class LodgingRepository
 */
class LodgingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Lodging::class);
    }

    /**
     * @param Hotel $hotel
     * @param User $user
     *
     * @return Lodging[]|null
     */
    public function findLodging(Hotel $hotel, User $user)
    {
        $query = $this->createQueryBuilder('l');
        $query->innerJoin('l.hotel', 'h');
        $query->innerJoin('l.users', 'u');
        $query->orderBy('l.createdAt', 'DESC')
            ->where('h.id = :hotelId')
            ->andWhere('u.id = :userId')
            ->setParameter('hotelId', $hotel->getId())
            ->setParameter('userId', $user->getId())
            ->andWhere('l.deletedAt IS NULL');
        $lodging = $query->getQuery()->getResult();

        return $lodging;
    }
}
