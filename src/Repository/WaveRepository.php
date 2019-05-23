<?php

namespace App\Repository;

use App\Entity\Group;
use App\Entity\Team;
use App\Entity\User;
use App\Entity\UserEmail;
use App\Entity\UserPhonenumber;
use App\Entity\Wave;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class WaveRepository
 */
class WaveRepository extends ServiceEntityRepository
{
    /**
     * UserRepository constructor.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Wave::class);
    }

    /**
     * Get the current wave.
     *
     * @return Wave|null
     */
    public function getCurrentWave(): ?Wave
    {
        $result = $this->createQueryBuilder('w')
            ->orderBy('w.start')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();

        return !empty($result) ? $result[0] : null;
    }

    public function getContacts($waveId)
    {
        $contacts = [];
        /** @var Wave $wave */
        $wave = $this->find($waveId);
        $groups = $wave->getGroups()->getValues();

        foreach ($groups as $group) {
            /** @var Group $group */
            $teams = $group->getTeams()->getValues();
            foreach ($teams as $team) {
                /** @var Team $team */
                $users = $team->getUsers()->getValues();
                foreach ($users as $user) {
                    $emails = [];
                    /** @var User $user */
                    $e = $user->getEmails()->filter(function ($email) use (&$contacts) {
                        /** @var UserEmail $email */
                        return $email->getIsPublic();
                    })->toArray();
                    /** @var UserEmail $email */
                    foreach ($e as $email) {
                        $emails[] = [
                            'id' => $email->getId(),
                            'email' => $email->getEmail(),
                            'is_public' => $email->getIsPublic(),
                            'is_primary' => $email->getIsPrimary(),
                        ];
                    }

                    $phonenumbers = [];
                    $p = $user->getPhonenumbers()->filter(function ($phonenumber) {
                        /** @var UserPhonenumber $phonenumber */
                        return $phonenumber->getIsPublic();
                    })->toArray();

                    /** @var UserPhonenumber $phonenumber */
                    foreach ($p as $phonenumber) {
                        $phonenumbers[] = [
                            'id' => $phonenumber->getId(),
                            'phonenumber' => $phonenumber->getPhonenumber(),
                            'country_code' => $phonenumber->getCountryCode(),
                            'is_public' => $phonenumber->getIsPublic(),
                        ];
                    }

                    $team = $user->getTeam();
                    $group = $team->getGroup();
                    $contacts[] = [
                        'id' => $user->getId(),
                        'username' => $user->getUsername(),
                        'first_name' => $user->getFirstName(),
                        'last_name' => $user->getLastName(),
                        'profile_picture' => $user->getProfilePicture()->asArray(),
                        'team' => [
                            'id' => $team->getId(),
                            'name' => $team->getName(),
                            'start_number' => $team->getStartNumber(),
                        ],
                        'group' => [
                            'id' => $group->getId(),
                            'name' => $group->getName(),
                        ],
                        'emails' => $emails,
                        'phonenumbers' => $phonenumbers,
                    ];
                }
            }
        }

        return $contacts;
    }
}
