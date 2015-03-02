<?php

namespace Diside\ProfileBundle\Validator\Constraints;

use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;

class CurrentUserPassword extends UserPassword
{
    public $message = 'error.wrong_current_password';
}
