<?php

namespace Diside\ProfileBundle\Controller;

use Doctrine\ORM\EntityManager;
use Diside\ProfileBundle\Mailer\Mailer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

class BaseController extends Controller
{

    /** @return Mailer $mailer */
    protected function getMailer()
    {
        return $this->get('profile.mailer');
    }

    protected function getUserClass()
    {
        return $this->getParameter('diside_profile.user_class');
    }

    protected function getUserManager()
    {
        return $this->get('profile.user_manager');
    }

    protected function getUserRepository()
    {
        return $this->getEntityManager()->getRepository($this->getUserClass());
    }

    /**
     * @return EntityManager
     */
    private function getEntityManager()
    {
        return $this->get('doctrine.orm.entity_manager');
    }

    protected function saveUser(UserInterface $user)
    {
        $em = $this->getEntityManager();
        $em->persist($user);
        $em->flush();
    }

    protected function getParameter($key)
    {
        return $this->container->getParameter($key);
    }

}
