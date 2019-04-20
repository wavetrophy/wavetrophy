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
    public function getCurrentWave()
    {
        $result = $this->createQueryBuilder('w')
            ->select('w.id', 'w.start', 'w.end', 'w.name', 'w.country')
            ->orderBy('w.start')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
        return $result;
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
                    /** @var User $user */
                    $emails = $user->getEmails()->filter(function ($email) use (&$contacts) {
                        /** @var UserEmail $email */
                        return $email->getIsPublic();
                    });
                    $phonenumbers = $user->getPhonenumbers()->filter(function ($phonenumber) {
                        /** @var UserPhonenumber $phonenumber */
                        return $phonenumber->getIsPublic();
                    });

                    $team = $user->getTeam();
                    $group = $team->getGroup();
                    $contacts[] = [
                        'id' => $user->getId(),
                        'username' => $user->getUsername(),
                        'first_name' => $user->getFirstName(),
                        'last_name' => $user->getLastName(),
                        'profile_picture' => $user->getProfilePicture(),
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
