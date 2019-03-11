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

        $payload = $event->getData();

        $payload['ip'] = $request->getClientIp();
        $payload['user_id'] = $user->getId();
        $payload['current_wave'] = $this->wave->getCurrentWave();

        $team = $user->getTeam();
        $payload['team_id'] = $team ? $team->getId() : null;

        $event->setData($payload);

        $header = $event->getHeader();

        $header['cty'] = 'JWT';

        $event->setHeader($header);
    }
}
