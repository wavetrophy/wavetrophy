<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    /**
     * UserRepository constructor.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Find all users that did not received a welcome email.
     *
     * @return User[]
     */
    public function findAllUsersThatDidNotReceivedWelcomeEmail(): array
    {
        return $this->createQueryBuilder('u')
            ->select('*')
            ->where('u.received_welcome_email = 0')
            ->getQuery()
            ->getResult();
    }

    /**
     * Set a password.
     *
     * @param User $user
     * @param string $password
     */
    public function setPassword(User $user, string $password): void
    {
        $user->setPlainPassword($password);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    /**
     * @param string $email
     *
     * @return bool
     */
    public function isEmailAlreadyRegistered(string $email): bool
    {
        $user = $this->createQueryBuilder('u')
            ->select('1')
            ->where('u.email = :email')
            ->setParameter('email', $email)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
        return $user !== null;
    }

    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
