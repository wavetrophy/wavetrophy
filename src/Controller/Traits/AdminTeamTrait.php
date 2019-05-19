<?php

namespace App\Controller\Traits;

use App\Entity\Team;
use App\Entity\User;
use Doctrine\ORM\EntityManager;

/**
 * Class AdminTeamTrait
 *
 * @property EntityManager $em;
 */
trait AdminTeamTrait
{
    /**
     * Persist entity hook
     *
     * @param Team $team
     */
    protected function persistTeamEntity(Team $team)
    {
        $em = $this->em;
        $team->getUsers()->forAll(function ($key, User $user) use ($team, $em) {
            $user->setTeam($team);
            $em->persist($user);

            return true;
        });

        $this->persistEntity($team);
    }

    /**
     * Update entity Hook
     *
     * @param Team $team
     */
    protected function updateTeamEntity(Team $team)
    {
        $em = $this->em;
        $uow = $em->getUnitOfWork();
        /** @var User[] $users */
        $users = $uow->getIdentityMap()[\App\Entity\User::class];
        foreach ($users as $user) {
            $user->setTeam(null);
            $em->persist($user);
        }
        $team->getUsers()->forAll(function ($key, User $user) use ($team, $em) {
            $user->setTeam($team);
            $em->persist($user);

            return true;
        });
        $this->persistEntity($team);
    }
}
