<?php

namespace App\Services;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class MailerService
{
    private $mailer;
    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }
    public function sendMail(string $from, string $to, string $subject, string $pathTemplate, array $context)
    {
        $email = new TemplatedEmail();
        $email
            ->from($from)
            ->to($to)
            ->subject($subject)
            ->htmlTemplate($pathTemplate)
            ->context($context);

        $this->mailer->send($email);
    }
}