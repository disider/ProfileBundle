<?php

namespace Diside\ProfileBundle\Controller;

use Diside\ProfileBundle\Helper\TokenGenerator;
use Diside\ProfileBundle\Model\UserInterface;
use Diside\ProfileBundle\Form\RequestResetPasswordForm;
use Diside\ProfileBundle\Form\ResetPasswordForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @Route("/reset-password")
 */
class ResetPasswordController extends BaseController
{

    /**
     * @Route("", name="reset_password_request")
     * @Template
     */
    public function requestResetPasswordAction(Request $request)
    {
        if ($this->getUser() != null) {
            return $this->redirect($this->generateUrl('homepage'));
        }

        $form = $this->createForm(new RequestResetPasswordForm($this->getUserClass()));

        if ($request->isMethod(Request::METHOD_POST)) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                /** @var UserInterface $user */
                $user = $form->getData();

                $user = $this->getUserRepository()->findOneByEmail($user->getUsername());

                if ($user) {
                    $user->setResetPasswordToken(TokenGenerator::generateToken());
                    $user->setPasswordRequestedAt(new \DateTime());

                    $this->saveUser($user);

                    $mailer = $this->getMailer();

                    $mailer->sendResetPasswordRequestEmailTo($user);

                    return new RedirectResponse($this->generateUrl('reset_password_request_sent'));
                }
            }
        }

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @Route("/request-sent", name="reset_password_request_sent")
     * @Template
     */
    public
    function resetPasswordRequestSentAction()
    {
        if ($this->getUser() != null) {
            return $this->redirect($this->generateUrl('homepage'));
        }

        return array();
    }

    /**
     * @Route("/reset/{token}", name="reset_password")
     * @Template
     */
    public function resetPasswordAction(Request $request, $token)
    {
        if ($this->getUser() != null) {
            return $this->redirect($this->generateUrl('homepage'));
        }

        $user = $this->getUserRepository()->findOneByResetPasswordToken($token);
        if(!$user)
            throw new NotFoundHttpException;

        $form = $this->createForm(new ResetPasswordForm($this->getUserClass()), $user);

        if ($request->isMethod(Request::METHOD_POST)) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                /** @var UserInterface $user */
                $user = $form->getData();

                $userManager = $this->getUserManager();

                $userManager->updateUser($user);

                return new RedirectResponse($this->generateUrl('reset_password_completed'));
            }
        }

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @Route("/thank-you", name="reset_password_completed")
     * @Template
     */
    public
    function resetPasswordCompletedAction()
    {
        if ($this->getUser() != null) {
            return $this->redirect($this->generateUrl('homepage'));
        }

        return array();
    }

}
