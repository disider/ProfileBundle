<?php

namespace Diside\ProfileBundle\Controller;

use Diside\ProfileBundle\Helper\TokenGenerator;
use Diside\ProfileBundle\Model\UserInterface;
use Doctrine\ORM\EntityManager;
use Diside\ProfileBundle\Form\RegistrationForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @Route("/register")
 */

class RegistrationController extends BaseController
{

    /**
     * @Route("", name="register")
     * @Template
     */
    public function registerAction(Request $request)
    {
        if ($this->getUser() != null) {
            return $this->redirect($this->generateUrl('homepage'));
        }

        $form = $this->createForm(new RegistrationForm($this->getUserClass()));

        if ($request->isMethod(Request::METHOD_POST)) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                /** @var UserInterface $user */
                $user = $form->getData();
                $user->setConfirmationToken(TokenGenerator::generateToken());

                $userManager = $this->getUserManager();

                $userManager->updateUser($user);

                $mailer = $this->getMailer();

                $mailer->sendConfirmRegistrationEmailTo($user);

                return new RedirectResponse($this->generateUrl('register_request_confirmation'));
            }
        }

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @Route("/request-confirmation", name="register_request_confirmation")
     * @Template
     */
    public function requestRegistrationConfirmationAction()
    {
        if ($this->getUser() != null) {
            return $this->redirect($this->generateUrl('homepage'));
        }

        return array();
    }

    /**
     * @Route("/confirm/{token}", name="register_confirm")
     * @Template
     */
    public function confirmRegistrationAction($token)
    {
        if ($this->getUser() != null) {
            return $this->redirect($this->generateUrl('homepage'));
        }

        /** @var UserInterface $user */
        $user = $this->getUserRepository()->findOneByConfirmationToken($token);
        if(!$user)
            throw new NotFoundHttpException(sprintf('User for token: %s not found', $token));

        $user->setEnabled(true);

        $this->saveUser($user);

        $mailer = $this->getMailer();

        $mailer->sendRegistrationCompletedEmailTo($user);

        return new RedirectResponse($this->generateUrl('register_thank_you'));
    }

    /**
     * @Route("/thank-you", name="register_thank_you")
     * @Template
     */
    public function registrationCompletedAction()
    {
        if ($this->getUser() != null) {
            return $this->redirect($this->generateUrl('homepage'));
        }

        return array();
    }

}
