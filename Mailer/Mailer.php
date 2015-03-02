<?php

namespace Diside\ProfileBundle\Mailer;

use Diside\ProfileBundle\Model\UserInterface;

interface Mailer {

    public function sendConfirmRegistrationEmailTo(UserInterface $user);

    public function sendRegistrationCompletedEmailTo(UserInterface $user);

    public function sendResetPasswordRequestEmailTo(UserInterface $user);
}