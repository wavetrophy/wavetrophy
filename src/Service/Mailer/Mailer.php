<?php

namespace App\Service\Mailer;

use Swift_Mailer;
use Swift_Message;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

/**
 * Class Mailer
 */
class Mailer
{
    private $mailer;
    private $twig;

    /**
     * Mailer constructor.
     *
     * @param Swift_Mailer $mailer
     */
    public function __construct(Swift_Mailer $mailer, EngineInterface $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    /**
     * Send an email to someone.
     *
     * @param string $subject
     * @param string $from
     * @param string $receiver
     * @param string $htmlTemplate
     * @param string $txtTemplate
     * @param array $data
     *
     * @return bool
     */
    public function sendMail(
        string $subject,
        string $from,
        string $receiver,
        string $htmlTemplate,
        string $txtTemplate,
        array $data
    ) {
        $message = (new Swift_Message($subject))
            ->setFrom($from)
            ->setTo($receiver)
            ->setBody(
                $this->twig->render(
                // templates/emails/registration.html.twig
                    $htmlTemplate,
                    $data
                ),
                'text/html'
            )
            ->addPart(
                $this->twig->render(
                    $txtTemplate,
                    $data
                ),
                'text/plain'
            );

        $this->mailer->send($message);

        return true;
    }
}
