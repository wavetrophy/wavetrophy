<?php

namespace App\Controller;

use App\Service\User\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class MailerController
 */
class MailerController extends AbstractController
{
    private $userService;

    /**
     * MailerController constructor.
     *
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @Route("/mailer/welcome-users")
     *
     * @return Response
     */
    public function sendWelcome()
    {
        $users = $this->userService->sendWelcomeEmails();

        $data = [];
        foreach ($users as $user) {
            $data['users'][] = [
                'first_name' => $user->getFirstName(),
                'last_name' => $user->getLastName(),
                'email' => $user->getEmail(),
            ];
        }
        $data['success'] = true;

        return $this->json($data);
    }
}
