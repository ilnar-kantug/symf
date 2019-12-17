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
     * @param string $defaultMailFrom
     */
    public function __construct(
        MailerInterface $mailer,
        Environment $twig,
        string $defaultMailFrom
    ) {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->mailFrom = $defaultMailFrom;
    }
}
