<?php

namespace App\Service\Wave;

use App\Entity\Wave;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class WaveHandler
 */
class WaveService
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * WaveService constructor.
     *
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Get the current wave
     */
    public function getCurrentWaveId()
    {
        $wave = $this->em->getRepository(Wave::class)
            ->getCurrentWave();
        if (empty($wave)) {
            return null;
        }
        return $wave->getId();

    }
}
