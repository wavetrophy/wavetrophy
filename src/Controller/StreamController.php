<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\EventRepository;
use App\Repository\HotelRepository;
use App\Repository\WaveRepository;
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
    private $eventRepository;
    private $logger;
    private $hotelRepository;
    private $waveRepository;

    /**
     * StreamController constructor.
     *
     * @param EventRepository $locationRepository
     * @param HotelRepository $hotelRepository
     * @param WaveRepository $waveRepository
     * @param LoggerInterface $logger
     */
    public function __construct(
        EventRepository $locationRepository,
        HotelRepository $hotelRepository,
        WaveRepository $waveRepository,
        LoggerInterface $logger
    ) {
        $this->eventRepository = $locationRepository;
        $this->hotelRepository = $hotelRepository;
        $this->waveRepository = $waveRepository;
        $this->logger = $logger;
    }

    /**
     * @Route("/api/users/{user}/stream", methods={"GET"}, name="api_users_get_stream")
     *
     * @param User $user
     *
     * @return JsonResponse
     */
    public function getStream(User $user): JsonResponse
    {
        $currentWave = $this->waveRepository->getCurrentWave();
        try {
            $events = $this->eventRepository->getEventsForUser($user, $currentWave);
            $hotels = $this->hotelRepository->getHotelsForUser($user, $currentWave);
        } catch (Exception $exception) {
            $this->logger->alert($exception->getMessage() . "\n" . $exception->getTraceAsString());
            return $this->json(['success' => false, 'message' => $exception->getMessage()]);
        }
        return $this->json(['events' => $events, 'hotels' => $hotels, 'success' => true]);
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
            $location = $this->eventRepository->getLocation($location, $user);
        } catch (Exception $exception) {
            $this->logger->alert($exception->getMessage() . "\n" . $exception->getTraceAsString());
            return $this->json(['success' => false, 'message' => $exception->getMessage()]);
        }
        return $this->json(['location' => $location, 'success' => true]);
    }
}
