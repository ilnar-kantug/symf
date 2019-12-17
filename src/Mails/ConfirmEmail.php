<?php

declare(strict_types=1);

namespace App\Mails;

use App\Entity\User;
use Symfony\Component\Mime\Email;

class ConfirmEmail extends Mailer
{
    public function send(User $user): void
    {
        $body = $this->twig->render(
            'emails/registration.html.twig',
            ['user' => $user]
        );

        $email = (new Email())
            ->from($this->mailFrom)
            ->to($user->getEmail())
            ->subject('Confirm your mail')
            ->html($body);

        $this->mailer->send($email);
    }
}
