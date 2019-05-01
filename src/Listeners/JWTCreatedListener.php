<?php

namespace App\Listeners;

use App\Entity\User;
use App\Repository\WaveRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class JWTCreatedListener
 */
class JWTCreatedListener
{
    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var WaveRepository
     */
    private $wave;

    /**
     * @param RequestStack $requestStack
     * @param WaveRepository $wave
     */
    public function __construct(
        RequestStack $requestStack,
        WaveRepository $wave
    ) {
        $this->requestStack = $requestStack;
        $this->wave = $wave;
    }

    /**
     * @param JWTCreatedEvent $event
     *
     * @return void
     */
    public function onJWTCreated(JWTCreatedEvent $event)
    {
        $request = $this->requestStack->getCurrentRequest();

        /** @var User $user */
        $user = $event->getUser();

        $profilePicture = $user->getProfilePicture();
        if (!empty($profilePicture)) {
            $profilePicture = $profilePicture->asArray();
        }

        $payload = $event->getData();

        $locale = $user->getLocale();

        $payload['ip'] = $request->getClientIp();
        $payload['user_id'] = $user->getId();
        $payload['profile_picture'] = $profilePicture;
        $payload['current_wave'] = $this->wave->getCurrentWave();
        $payload['locale'] = [
            'short' => mb_substr($locale, 0, 2),
            'long' => $locale,
        ];
        $payload['team_id'] = null;
        $payload['group_id'] = null;

        $team = $user->getTeam();
        if (!empty($team)) {
            $payload['team_id'] = $team->getId();
            $payload['group_id'] = $team->getGroup()->getId();
        }

        $event->setData($payload);

        $header = $event->getHeader();

        $header['cty'] = 'JWT';

        $event->setHeader($header);
    }
}
