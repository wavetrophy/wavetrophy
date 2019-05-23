<?php

namespace App\Repository;

use App\Entity\Media;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class MediaRepository
 */
class MediaRepository extends ServiceEntityRepository
{
    /**
     * MediaRepository constructor.
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Media::class);
    }

    /**
     * @param string $url
     *
     * @return Media|null
     */
    public function findByUrl(string $url)
    {
        $query = $this->createQueryBuilder('m');
        $query->where('m.url = :url')
            ->setParameter('url', $url)
            ->andWhere('m.deletedAt IS NULL');
        $result = $query->getQuery()->getResult();
        return $result;
    }
}
