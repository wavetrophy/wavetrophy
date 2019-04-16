<?php

namespace App\Repository;

use App\Entity\UserPhonenumber;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class UserPhonenumberRepository
 */
class UserPhonenumberRepository extends ServiceEntityRepository
{
    /**
     * UserRepository constructor.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UserPhonenumber::class);
    }

    /**
     * @param array $criteria
     *
     * @return array
     */
    public function isUnique(array $criteria): array
    {
        if (!isset($criteria['phonenumber']) || !isset($criteria['countryCode'])) {
            return [];
        }

        $phonenumber = preg_replace('/\s+/', '', trim($criteria['phonenumber']));
        $countryCode = preg_replace('/\s+/', '', trim($criteria['countryCode']));
        $query = $this->createQueryBuilder('up');
        $query->where('up.phonenumber = :phonenumber')
            ->andWhere('up.countryCode = :countryCode')
            ->setParameter('phonenumber', $phonenumber)
            ->setParameter('countryCode', $countryCode);
        $result = $query->getQuery()->getResult();
        return $result;
    }
}
