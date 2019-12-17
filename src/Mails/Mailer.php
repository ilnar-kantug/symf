<?php

declare(strict_types=1);

namespace App\Mails;

use Symfony\Component\Mailer\MailerInterface;
use Twig\Environment;

abstract class Mailer
{
    /**
     * @var MailerInterface
     */
    protected $mailer;
    /**
     * @var Environment
     */
    protected $twig;
    /**
     * @var string
     */
    protected $mailFrom;

    /**
     * Mailer constructor.
     * @param MailerInterface $mailer
     * @param Environment $twig
     */
    public function __construct(
        MailerInterface $mailer,
        Environment $twig
    ) {
        $this->mailer = $mailer;
        $this->twig = $twig;
        //TODO to configs
        $this->mailFrom = 'me@me.me';
    }
}
