<?php

namespace App\Controller;

use App\Entity\Wave;
use App\Repository\WaveRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ContactController
 */
class ContactController extends AbstractController
{
    /**
     * @var WaveRepository
     */
    private $repository;

    /**
     * ContactController constructor.
     *
     * @param WaveRepository $repository
     */
    public function __construct(WaveRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @Route("/api/waves/{wave}/contacts", methods={"GET"}, name="api_wave_get_contacts")
     *
     * @param Wave $wave
     *
     * @return JsonResponse
     */
    public function getContacts(Wave $wave): JsonResponse
    {
        $contacts = $this->repository->getContacts($wave->getId());

        return $this->json(['contacts' => $contacts, 'success' => true]);
    }
}
