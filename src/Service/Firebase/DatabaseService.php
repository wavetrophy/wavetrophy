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
            $data = $document->data();
            $tokens[] = [
                'token' => array_key_exists('token', $data) ? $data['token'] : null,
                'platform' => array_key_exists('platform', $data) ? $data['platform'] : null,
            ];
        }

        return $tokens;
    }
}
