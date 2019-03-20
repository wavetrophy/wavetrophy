<?php

namespace App\Service\User;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\Mailer\Mailer;

/**
 * Class UserService
 */
class UserService
{
    private $userRepository;
    private $mailer;

    /**
     * UserService constructor.
     *
     * @param UserRepository $userRepository
     * @param Mailer $mailer
     */
    public function __construct(UserRepository $userRepository, Mailer $mailer)
    {
        $this->userRepository = $userRepository;
        $this->mailer = $mailer;
    }

    /**
     * Send welcome emails.
     *
     * @return User[]
     */
    public function sendWelcomeEmails(): array
    {
        $users = $this->userRepository->findAllUsersThatDidNotReceivedWelcomeEmail();
        foreach ($users as $user) {
            $this->userRepository->setHasRececeivedWelcomeEmail($user, true);
            $email = $user->getEmail();
            $token = $this->userRepository->generateTokenForUserEmail($email);

            $data = [
                'first_name' => $user->getFirstName(),
                'token' => $token,
            ];
            $this->mailer->sendMail(
                '[WAVETROPHY] Willkommen',
                'noreply@wavetrophy.com',
                $email->getEmail(),
                'emails/registration.html.twig',
                'emails/registration.txt.twig',
                $data
            );
        }
        return $users;
    }

    /**
     * @param string $token
     *
     * @return bool
     */
    public function confirmEmail(string $token)
    {
        $user = $this->userRepository->confirmEmail($token);

        $password = $this->generatePassword();
        $this->userRepository->setPassword($user, $password);

        $data = [
            'first_name' => $user->getFirstName(),
            'username' => $user->getUsername(),
            'password' => $password,
        ];

        $this->mailer->sendMail(
            '[WAVETROPHY] Vorbereitung',
            'noreply@wavetrophy.com',
            $user->getEmail()->__toString(),
            'emails/app-setup.html.twig',
            'emails/app-setup.txt.twig',
            $data
        );

        return true;
    }

    /**
     * Generate a random password
     *
     * @return string
     */
    private function generatePassword(): string
    {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = []; //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }
}
