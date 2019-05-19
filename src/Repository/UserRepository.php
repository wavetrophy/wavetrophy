<?php

namespace App\Repository;

use App\Entity\Team;
use App\Entity\User;
use App\Entity\UserEmail;
use App\Exception\ResourceNotFoundException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use FOS\UserBundle\Model\UserInterface;
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
     * @param int $waveId
     *
     * @return mixed
     */
    public function findUsersForWave(?int $waveId)
    {
        $query = $this->createQueryBuilder('u');
        $query->join('u.team', 't');
        $query->join('t.group', 'g');
        $query->join('g.wave', 'w');
        $query->where('w.id = :waveId');
        $query->setParameter('waveId', $waveId);
        $query = $query->getQuery();
        $result = $query->getResult();

        return $result;
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
     * @param string $username
     *
     * @return UserInterface
     */
    public function findByUsername(string $username): UserInterface
    {
        return $this->findOneBy(['username' => $username]);
    }

    /**
     * @param string $username
     *
     * @return User|UserEmail|mixed|null
     */
    public function findUserByEmailsOrUsername(string $username)
    {
        $query = $this->createQueryBuilder('u');
        $query->where('u.username = :username');
        $query->setParameter('username', $username);
        $query = $query->getQuery();
        $result = $query->getResult();
        if (!empty($result)) {
            return $result[0];
        }

        $query = $this->_em->getRepository(UserEmail::class)->createQueryBuilder('ue');
        $query->where('ue.email = :email')
            ->andWhere('ue.deletedAt IS NULL OR ue.deletedAt <= :now');
        $query->setParameter('email', $username);
        $query->setParameter('now', date('Y-m-d H:i:s'));
        $query = $query->getQuery();
        /** @var UserEmail|null $result */
        $result = $query->getResult();
        if (empty($result)) {
            return null;
        }

        return $result[0]->getUser();
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
