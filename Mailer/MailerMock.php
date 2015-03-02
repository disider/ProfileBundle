<?php

namespace Diside\ProfileBundle\Mailer;

use Diside\ProfileBundle\Model\UserInterface;

class MailerMock implements Mailer
{
    private $template;
    private $to;

    public function sendConfirmRegistrationEmailTo(UserInterface $user) {
        $this->registerMail('registration_confirm', $user->getEmail());
    }

    public function sendRegistrationCompletedEmailTo(UserInterface $user)
    {
        $this->registerMail('registration_completed', $user->getEmail());
    }

    public function sendResetPasswordRequestEmailTo(UserInterface $user)
    {
        $this->registerMail('request_reset_password', $user->getEmail());
    }

    public function getTemplate()
    {
        return $this->template;
    }

    public function getTo()
    {
        return $this->to;
    }

    protected function registerMail($template, $email)
    {
        $this->template = $template;
        $this->to = $email;
    }
}