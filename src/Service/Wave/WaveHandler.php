<?php

namespace App\Service\Wave;

use App\Entity\User;
use App\Entity\Wave;
use DateTime;
use Exception;

/**
 * Class WaveHandler
 */
class WaveHandler
{
    /**
     * Archive a wave.
     *
     * @param Wave $wave
     * @param User $user
     * @throws Exception
     */
    public function archive(Wave $wave, User $user)
    {
        $wave->setArchivedAt(new DateTime());
        $wave->setArchivedBy($user->getId());
    }
}
