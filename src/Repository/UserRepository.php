<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\UserEmail;
use App\Exception\ResourceNotFoundException;
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
        $query = $this->createQueryBuilder('u');
        $query->where('u.hasReceivedWelcomeEmail = 0');
        $query = $query->getQuery();
        $result = $query->getResult();

        return $result;
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

    /**
     * @param UserEmail $userEmail
     *
     * @return string
     */
    public function generateTokenForUserEmail(UserEmail $userEmail): string
    {
        $token = uniqid($userEmail->getId());
        $userEmail->setConfirmationToken($token);
        $this->_em->persist($userEmail);
        $this->_em->flush();
        return $token;
    }

    /**
     * @param string $token
     *
     * @return User
     * @throws ResourceNotFoundException
     */
    public function confirmEmail(string $token): User
    {
        /** @var UserEmail[]|null $email */
        $email = $this->_em->getRepository(UserEmail::class)->findBy(['confirmationToken' => $token]);
        if (!$email) {
            throw new ResourceNotFoundException('Confirmation token not found');
        }
        $email = array_shift($email);

        $email->setConfirmationToken(null);
        $email->setConfirmed(true);

        $user = $email->getUser();

        $this->_em->persist($email);
        $this->_em->flush();

        return $user;
    }

    /**
     * Set has received welcome email flag.
     *
     * @param User $user
     * @param bool|null $received
     */
    public function setHasRececeivedWelcomeEmail(User $user, ?bool $received = true): void
    {
        $user->setHasReceivedWelcomeEmail($received);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    /**
     * @param User $user
     * @param bool|null $received
     */
    public function setHasReceivedSetupAppEmail(User $user, ?bool $received = true): void
    {
        $user->setHasReceivedSetupAppEmail($received);
        $this->_em->persist($user);
        $this->_em->flush();
    }
}
