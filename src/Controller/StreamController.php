<?php

namespace App\Controller;

use App\Repository\LocationRepository;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserController
 */
class StreamController extends AbstractController
{
    private $locationRepository;

    private $logger;

    /**
     * StreamController constructor.
     *
     * @param LocationRepository $locationRepository
     * @param LoggerInterface $logger
     */
    public function __construct(LocationRepository $locationRepository, LoggerInterface $logger)
    {
        $this->locationRepository = $locationRepository;
        $this->logger = $logger;
    }

    /**
     * @Route("/api/users/{user}/stream", methods={"GET"}, name="api_users_get_stream")
     *
     * @param string $user
     *
     * @return JsonResponse
     */
    public function getStream(string $user): JsonResponse
    {
        try {
            $locations = $this->locationRepository->getLocationsForUser($user);
        } catch (Exception $exception) {
            $this->logger->alert($exception->getMessage() . "\n" . $exception->getTraceAsString());
            return $this->json(['success' => false, 'message' => $exception->getMessage()]);
        }
        return $this->json(['locations' => $locations, 'success' => true]);
    }

    /**
     * @Route("/api/users/{user}/stream/{location}", methods={"GET"}, name="api_users_get_stream_location")
     *
     * @param string $user
     *
     * @return JsonResponse
     */
    public function getLocation(string $user, string $location): JsonResponse
    {
        try {
            $location = $this->locationRepository->getLocation($location, $user);
        } catch (Exception $exception) {
            $this->logger->alert($exception->getMessage() . "\n" . $exception->getTraceAsString());
            return $this->json(['success' => false, 'message' => $exception->getMessage()]);
        }
        return $this->json(['location' => $location, 'success' => true]);
    }
}
