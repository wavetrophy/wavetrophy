<?php

namespace App\Repository;

use App\Entity\UserEmail;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class UserEmailRepository
 */
class UserEmailRepository extends ServiceEntityRepository
{
    /**
     * UserEmailRepository constructor.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UserEmail::class);
    }

    /**
     * @param string|null $email
     *
     * @return UserEmail|null
     */
    public function findEmailByString(?string $email): ?UserEmail
    {
        $result = $this->createQueryBuilder('ue')
            ->where('ue.email = :email')
            ->setParameter('email', $email)
            ->getQuery()
            ->getResult();
        return !empty($result) ? $result[0] : null;
    }
}
