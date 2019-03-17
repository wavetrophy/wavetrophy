<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserController
 */
class StreamController extends AbstractController
{
    /**
     * StreamController constructor.
     */
    public function __construct()
    {
    }

    /**
     * @Route("/api/users/{user}/stream", methods={"GET"})
     *
     * @return Response
     */
    public function stream(string $userId): Response
    {
        return $this->json([]);
    }
}
