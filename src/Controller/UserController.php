<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserController
 */
class UserController extends AbstractController
{
    private $userRepository;

    private $logger;

    /**
     * StreamController constructor.
     *
     * @param UserRepository $userRepository
     * @param LoggerInterface $logger
     */
    public function __construct(UserRepository $userRepository, LoggerInterface $logger)
    {
        $this->userRepository = $userRepository;
        $this->logger = $logger;
    }

    /**
     * @Route("/api/users/{user}/password", methods={"PUT"}, name="api_users_update_password")
     *
     * @param Request $request
     * @param string $user
     *
     * @return JsonResponse
     */
    public function setPassword(Request $request, string $user): JsonResponse
    {
        /** @var User $user */
        $user = $this->userRepository->find($user);

        $body = json_decode($request->getContent(), true);
        if (!array_key_exists('password', $body)) {
            return $this->json(['errors' => ['title' => 'password key must be set']]);
        }
        $password = $body['password'];

        $user->setPlainPassword($password, false);

        $em = $this->getDoctrine()->getManager('default');
        $em->persist($user);
        $em->flush();

        return $this->json(['success' => true]);
    }
}