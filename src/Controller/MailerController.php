<?php

namespace App\Controller;

use App\Exception\ResourceNotFoundException;
use App\Service\User\UserService;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class MailerController
 */
class MailerController extends AbstractController
{
    private $userService;
    private $logger;

    /**
     * MailerController constructor.
     *
     * @param UserService $userService
     */
    public function __construct(UserService $userService, LoggerInterface $logger)
    {
        $this->userService = $userService;
        $this->logger = $logger;
    }

    /**
     * @Route("/mailer/welcome-users", methods={"POST"})
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

    /**
     * @Route("/signup/confirm/{token}", methods={"GET"}, name="mailer_confirm_email")
     *
     * @param string $token
     *
     * @return Response
     */
    public function viewConfirm(string $token)
    {
        return $this->render('mailer/confirm.html.twig');
    }

    /**
     * @Route("/signup/confirm/{token}", methods={"POST"}, name="mailer_confirm_email_post")
     *
     * @param string $token
     *
     * @return Response
     */
    public function confirm(string $token)
    {
        try {
            $this->userService->confirmEmail($token);
        } catch (ResourceNotFoundException $exception) {
            $errorId = uniqid('EC');
            $this->logger->error("ERRORID: {$errorId}\nTOKEN: {$token}\n".$exception->getMessage() . "\n" . $exception->getTraceAsString());
            return $this->render('mailer/confirm.html.twig', ['error' => true, 'error_id' => $errorId]);
        }

        return $this->redirectToRoute('mailer_confirm_email_thank_you');
    }

    /**
     * @Route("/signup/thanks", methods={"GET"}, name="mailer_confirm_email_thank_you")
     *
     * @return Response
     */
    public function viewThankYou()
    {
        return $this->render('mailer/confirm-thank-you.html.twig');
    }
}
