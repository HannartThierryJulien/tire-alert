<?php

namespace app\components;

use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\Transport;

/**
 * Classe permettant d'envoyer un mail sur l'adresse http://localhost:8025/.
 * Nécessite d'avoir lancé MailHog.exe téléchargeable  ici https://github.com/mailhog/MailHog/releases/download/v1.0.0/MailHog_windows_amd64.exe
 * Une fois MailHog téléchargé et lancé, il suffit de se rendre sur l'adresse mentionnée pour consulter les mails.
 */
class MailHog
{
    public static function sendEmail($from, $to, $subject, $message)
    {
        $transport = Transport::fromDsn('smtp://localhost:1025');
        $mailer = new Mailer($transport);

        $email = (new Email())
            ->from($from)
            ->to($to)
            ->subject($subject)
            ->text($message);

        $mailer->send($email);
    }
}