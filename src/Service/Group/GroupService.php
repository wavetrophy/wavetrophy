<?php

namespace App\Service\Group;

use App\Entity\Group;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class GroupService
 */
class GroupService
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * GroupService constructor.
     *
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param int $waveId
     *
     * @return Group[]
     */
    public function getGroupsForWave(int $waveId)
    {
        return $this->em->getRepository(Group::class)
            ->getByWave($waveId);
    }
}
