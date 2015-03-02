<?php

namespace Diside\ProfileBundle\Controller;

use Diside\ProfileBundle\Model\UserInterface;
use Diside\ProfileBundle\Form\ChangePasswordForm;
use Diside\ProfileBundle\Form\ProfileForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/profile")
 */
class ProfileController extends BaseController
{

    /**
     * @Route("", name="profile_edit")
     * @Template
     */
    public function editAction(Request $request)
    {
        return $this->processForm($request);
    }

    /**
     * @Route("/change-password", name="profile_change_password")
     * @Template
     */
    public function changePasswordAction(Request $request)
    {
        $form = $this->createForm(new ChangePasswordForm($this->getUserClass()), $this->getUser());

        if ($request->isMethod(Request::METHOD_POST)) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                /** @var UserInterface $user */
                $user = $form->getData();

                $userManager = $this->getUserManager();

                $userManager->updateUser($user);

                $this->addFlash('success', 'flash.password.updated');

                return $this->redirect($this->generateUrl('profile_edit'));
            }
        }

        return array(
            'form' => $form->createView()
        );
    }

    private function processForm(Request $request)
    {
        $user = $this->getUser();

        //$this->checkOwnership($user);

        $form = $this->createForm(new ProfileForm($this->getUserClass()), $user);

        if ($request->isMethod(Request::METHOD_POST)) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                /** @var User $user */
                $user = $form->getData();

                $this->saveUser($user);

                $this->addFlash(
                    'success', 'flash.profile.updated'
                );

                if ($form->get('save_and_close')->isClicked()) {
                    return $this->redirect($this->generateUrl('homepage'));
                }

                return $this->redirect($this->generateUrl('profile_edit'));
            }
        }

        return array(
            'user' => $user,
            'form' => $form->createView()
        );
    }

}
