<?php

namespace App\Listeners;

use Gedmo\Blameable\BlameableListener as BlameableListenerBase;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class BlameableListener
 */
class BlameableListener extends BlameableListenerBase
{
    /**
     * @var TokenStorageInterface
     */
    protected $securityStorage;

    public function getFieldValue($meta, $field, $eventAdapter)
    {
        if ($this->securityStorage) {
            $token = $this->securityStorage->getToken();
            if (is_callable([$token, 'getUser'])) {
                return $token->getUser();
            }
        }

        return null;
    }

    public function setSecurityStorage($securityStorage)
    {
        $this->securityStorage = $securityStorage;
    }
}
