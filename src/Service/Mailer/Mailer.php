<?php

namespace App\Service\Mailer;

use Mailgun\Mailgun;
use Psr\Container\ContainerInterface;
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
     * @param EngineInterface $twig
     * @param Mailgun $mailer
     */
    public function __construct(EngineInterface $twig, Mailgun $mailer)
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
        $this->mailer->messages()->send(getenv('MAILGUN_DOMAIN'), [
            'from' => $from,
            'to' => $receiver,
            'subject' => $subject,
            'text' => $this->twig->render(
                $txtTemplate,
                $data
            ),
            'html' => $this->twig->render(
            // templates/emails/registration.html.twig
                $htmlTemplate,
                $data
            ),
        ]);

        return true;
    }
}
