<?php

namespace App\Controller;

use App\Service\Firebase\DatabaseService;
use App\Service\Firebase\NotificationService;
use Moment\Moment;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class NotificationController
 */
class NotificationController extends AbstractController
{
    /**
     * @var NotificationService
     */
    private $notification;
    /**
     * @var DatabaseService
     */
    private $firebaseDB;
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * NotificationController constructor.
     *
     * @param DatabaseService $firebaseDB
     * @param NotificationService $notifications
     * @param LoggerInterface $logger
     */
    public function __construct(
        DatabaseService $firebaseDB,
        NotificationService $notifications,
        LoggerInterface $logger
    ) {
        $this->notification = $notifications;
        $this->firebaseDB = $firebaseDB;
        $this->logger = $logger;
    }

    /**
     * @Route("/notifications", methods={"POST"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function schedule(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        if (!array_key_exists('type', $data)
            || !array_key_exists('data', $data)
            || !array_key_exists('message', $data)
            || !array_key_exists('scheduled', $data)
            || !array_key_exists('day', $data)
            || !array_key_exists('time', $data)
        ) {
            return $this->json([
                'success' => false,
                'message' => 'type, data, message, scheduled, day, time required',
            ]);
        }

        $date = null;
        if ($data['scheduled']) {
            $now = new Moment();
            list($hours, $minutes) = explode(':', $data['time'], 2);
            $now->setHour((int)$hours);
            $now->setMinute((int)$minutes);
            if ($data['day'] > 0) {
                $now = $now->addDays($data['day']);
            }
            $date = $now->setTimezone('UTC')->format('Y-m-d[T]H:i:s.0000[Z]');
        }

        $scheduled = $data['scheduled'] ? "{$data['day']} {$data['time']}" : "Not scheduled";
        switch ($data['type']) {
            case 'topic':
                $this->logger->info("sending notification\ntopic: {$data['data']}\nScheduled: {$scheduled}");
                $this->notification->toTopic($data['data'], $data['message'], [
                    'scheduled' => $data['scheduled'],
                    'schedule' => $date,
                ]);
                break;
            case 'user':
                $tokens = $this->firebaseDB->getDeviceTokensForUser($data['data']);
                foreach ($tokens as $token) {
                    $this->logger->info("sending notification\nuser:{$data['data']}\ndevice:{$token['token']}\nScheduled: {$scheduled}");
                    $this->notification->toDevice($token['token'], $data['message'], [
                        'scheduled' => $data['scheduled'],
                        'schedule' => $date,
                    ]);
                }
                break;
            default:
                break;
        }

        return $this->json(['success' => true]);
    }
}
