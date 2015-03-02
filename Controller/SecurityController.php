<?php

namespace Diside\ProfileBundle\Controller;

use Diside\ProfileBundle\Form\LoginForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;

class SecurityController extends Controller
{

    /**
     * @Route("/login", name="login")
     * @Template
     */
    public function loginAction(Request $request)
    {
        if ($this->get('security.context')->isGranted('ROLE_USER')) {
            return $this->redirect($this->generateUrl('homepage'));
        }

        $targetPath = $request->headers->get('referer');

        $form = $this->createForm(new LoginForm($this->get('session')), null, array('target_path' => $targetPath));

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @Route("/login_check", name="login_check", options={"i18n"=false})
     */
    public function loginCheckAction()
    {
        throw new \Exception("Undefined method");
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction()
    {
        throw new \Exception('Undefined method');
    }
}
