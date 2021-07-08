<?php


namespace App\Models;


class Notifications
{
    private $mailer;

    public function __construct(Mail $mailer)
    {
        $this->mailer = $mailer;
    }

    public function emailWasChanged($email, $selector, $token)
    {
        $message = 'https://gallery/verify-email?selector=' . \urlencode($selector) . '&token=' . \urlencode($token);

        $this->mailer->send($email, $message);
    }

    public function passwordReset($email, $selector, $token)
    {
        $message = 'https://gallery/password-recovery/form?selector=' . \urlencode($selector) . '&token=' . \urlencode($token);

        $this->mailer->send($email, $message);
    }
}