<?php

namespace App\Service\User;

use App\Entity\User;
use App\Repository\UserEmailRepository;
use App\Repository\UserRepository;
use App\Service\Mailer\Mailer;

/**
 * Class UserService
 */
class UserService
{
    private $userRepository;
    private $mailer;
    private $userEmailRepository;

    /**
     * UserService constructor.
     *
     * @param UserRepository $userRepository
     * @param UserEmailRepository $userEmailRepository
     * @param Mailer $mailer
     */
    public function __construct(
        UserRepository $userRepository,
        UserEmailRepository $userEmailRepository,
        Mailer $mailer
    ) {
        $this->userRepository = $userRepository;
        $this->userEmailRepository = $userEmailRepository;
        $this->mailer = $mailer;
    }

    /**
     * @param int $waveId
     *
     * @return mixed
     */
    public function getWaveParticipants(?int $waveId)
    {
        return $this->userRepository->findUsersForWave($waveId);
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
            $email = $this->userEmailRepository->findEmailByString($user->getEmail());
            $email->setConfirmed(true);

            $password = $this->generatePassword();
            $this->userRepository->setPassword($user, $password);

            $data = [
                'first_name' => $user->getFirstName(),
                'username' => $user->getUsername(),
                'password' => $password,
            ];

            $this->mailer->sendMail(
                '[WAVETROPHY] Vorbereitung',
                getenv('MAILGUN_FROM'),
                $user->getEmail(),
                'emails/app-setup.html.twig',
                'emails/app-setup.txt.twig',
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
            getenv('MAILGUN_FROM'),
            $user->getEmail(),
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
