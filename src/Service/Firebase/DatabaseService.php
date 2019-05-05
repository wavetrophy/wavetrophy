<?php

namespace App\Service\Firebase;

use Google\Cloud\Firestore\FirestoreClient;
use Psr\Container\ContainerInterface;

/**
 * Class DatabaseService
 */
class DatabaseService extends FirebaseService
{
    private $client;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        $this->client = new FirestoreClient([
            'keyFilePath' => $container->get('service_container')->getParameter('firebase_config_file'),
        ]);
    }

    public function getDeviceTokensForUser(int $userId)
    {
        $devicesRef = $this->client->collection('devices');
        $query = $devicesRef->where('userId', '==', $userId);
        $snapshot = $query->documents();
        $tokens = [];
        foreach ($snapshot as $document) {
            $tokens[] = [
                'token' => $document->deviceToken(),
                'platform' => $document->platform(),
            ];
        }

        return $tokens;
    }
}
