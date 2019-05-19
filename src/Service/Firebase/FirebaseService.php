<?php

namespace App\Service\Firebase;

use App\Entity\User;
use Kreait\Firebase;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Psr\Container\ContainerInterface;

/**
 * Class Firebase
 */
abstract class FirebaseService
{
    /**
     * @var User[null
     */
    private $currentUser;
    /**
     * @var Firebase
     */
    private $firebase;
    /**
     * @var ServiceAccount
     */
    private $serviceAccount;

    /**
     * Firebase constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $token = $container->get('security.token_storage')->getToken();
        $user = null;
        if (!empty($token)) {
            $user = $token->getUser();
        }
        $this->currentUser = $user;

        $this->serviceAccount = ServiceAccount::fromJsonFile($container->get('service_container')->getParameter('firebase_config_file'));
        $this->firebase = (new Factory)
            ->withServiceAccount($this->serviceAccount)
            ->create();
    }

    /**
     * Get the firebase cloud messaging
     *
     * @return Firebase\Messaging
     */
    protected function getMessaging()
    {
        return $this->firebase->getMessaging();
    }

    /**
     * Get all data of the currently logged in user
     *
     * @return array
     */
    protected function getCurrentUserData()
    {
        $data = [];
        $userIsAvailable = !empty($this->currentUser);
        if ($userIsAvailable) {
            $data = [
                'id' => (string)$this->currentUser->getId(),
                'username' => $this->currentUser->getUsername(),
                'lastname' => $this->currentUser->getLastName(),
                'firstname' => $this->currentUser->getFirstName(),
                'profile_picture_id' => (string)$this->currentUser->getProfilePicture()->getId(),
                'profile_picture_url' => $this->currentUser->getProfilePicture()->getUrl(),
                'profile_picture_name' => $this->currentUser->getProfilePicture()->getName(),
                'profile_picture_path' => $this->currentUser->getProfilePicture()->getPath(),
                'locale' => $this->currentUser->getLocale(),
            ];
        }
        return $data;
    }
}
