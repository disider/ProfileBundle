<?php

namespace Diside\ProfileBundle\Mailer;

use Diside\ProfileBundle\Model\UserInterface;
use Swift_Mailer;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Bundle\TwigBundle\TwigEngine;

class DefaultMailer implements Mailer
{
    /** @var TwigEngine */
    private $twigEngine;

    /** @var Router */
    private $router;

    /** @var Swift_Mailer */
    private $mailer;

    /** @var array */
    private $displayNames;

    /** @var array */
    private $emails;

    public function __construct(TwigEngine $twigEngine, Router $router, Swift_Mailer $mailer, array $displayNames, array $emails)
    {
        $this->twigEngine = $twigEngine;
        $this->router = $router;
        $this->mailer = $mailer;
        $this->displayNames = $displayNames;
        $this->emails = $emails;
    }

    public function sendConfirmRegistrationEmailTo(UserInterface $user)
    {
        $this->sendHtml('Diside\ProfileBundle:Registration:confirmEmail', $user->getEmail(), array(
            'url' => $this->generateUrl('register_confirm', array(
                'token' => $user->getConfirmationToken()
            )),
            'user' => $user->getEmail(),
        ));
    }

    public function sendRegistrationCompletedEmailTo(UserInterface $user)
    {
        $this->sendHtml('Diside\ProfileBundle:Registration:registrationCompletedEmail', $user->getEmail(), array(
            'user' => $user->getEmail(),
        ));
    }

    public function sendResetPasswordRequestEmailTo(UserInterface $user)
    {
        $this->sendHtml('Diside\ProfileBundle:ResetPassword:requestResetPasswordEmail', $user->getEmail(), array(
            'url' => $this->generateUrl('reset_password', array(
                'token' => $user->getResetPasswordToken()
            )),
            'user' => $user->getEmail(),
        ));
    }

    protected function send($template, $email, array $params = array())
    {
        /** @var \Swift_Message $message */
        $message = $this->composeMessage($template, $email, $params);

        $this->mailer->send($message);
    }

    protected function sendHtml($template, $email, array $params = array())
    {
        /** @var \Swift_Message $message */
        $message = $this->composeMessage($template, $email, $params);

        $htmlTemplate = $this->twigEngine->render($template . '.html.twig', $params);
        $message->addPart($htmlTemplate, 'text/html');

        $this->mailer->send($message);
    }

    protected function generateUrl($route, $params, $isAbsolute = true)
    {
        return $this->router->generate($route, $params, $isAbsolute);
    }

    private function getFullEmailAddress($name)
    {
        return array($this->emails[$name] => $this->displayNames[$name]);
    }

    private function composeMessage($template, $email, array $params = array())
    {
        $subjectTemplate = $this->twigEngine->render($template . '.subject.twig', $params);
        $textTemplate = $this->twigEngine->render($template . '.body.twig', $params);

        /** @var \Swift_Message $message */
        $message = \Swift_Message::newInstance()
            ->setSubject($subjectTemplate)
            ->setFrom($this->getFullEmailAddress('no-reply'))
            ->setTo($email)
            ->setBody($textTemplate);

        return $message;
    }
}