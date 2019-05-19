<?php

namespace App\Controller;

use App\Entity\Team;
use App\Entity\Wave;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class GetTeamsForWaveAction
 */
class GetTeamsForWaveAction extends AbstractController
{
    /**
     * @Route("/waves/{wave}/teams", methods={"GET"})
     *
     * @param Wave $wave
     *
     * @return Response
     */
    public function getTeams(Wave $wave)
    {
        $teams = $this->getDoctrine()
            ->getRepository(Team::class)
            ->getWaveTeams($wave->getId());

        return $this->json($teams);
    }
}
