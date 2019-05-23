<?php

namespace App\Repository;

use App\Entity\Event;
use App\Entity\EventActivity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method EventActivity|null find($id, $lockMode = null, $lockVersion = null)
 * @method EventActivity|null findOneBy(array $criteria, array $orderBy = null)
 * @method EventActivity[]    findAll()
 * @method EventActivity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventActivityRepository extends ServiceEntityRepository
{
    /**
     * EventActivityRepository constructor.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, EventActivity::class);
    }

    /**
     * @param Event $event
     *
     * @return EventActivity[]|null
     */
    public function findAllForEvent(Event $event): ?array
    {
        return $this->createQueryBuilder('ea')
            ->innerJoin('ea.event', 'e')
            ->where('e.id = :id')
            ->setParameter('id', $event->getId())
            ->andWhere('ea.deletedAt IS NULL')
            ->getQuery()
            ->getResult();
    }
}
