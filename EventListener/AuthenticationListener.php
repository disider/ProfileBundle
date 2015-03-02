<?php

namespace Diside\ProfileBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Exception\AccountStatusException;
use Symfony\Component\Security\Core\SecurityContextInterface;

class AuthenticationListener implements EventSubscriberInterface
{
    /**
     * @var SessionInterface
     */
    protected $session;

    /**
     * @param SessionInterface $session
     */
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * @param FormEvent $event
     */
    public function onFormSetData(FormEvent $event)
    {
        $error = $this->session->get(SecurityContextInterface::AUTHENTICATION_ERROR);

        // Remove error so it isn't persisted
        $this->session->remove(SecurityContextInterface::AUTHENTICATION_ERROR);
        if ($error) {
            $event->getForm()->addError(new FormError($error->getMessage()));
        }
        $event->setData(
            array(
                '_username' => $this->session->get(SecurityContextInterface::LAST_USERNAME),
            )
        );
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            FormEvents::PRE_SET_DATA => 'onFormSetData',
        );
    }
}
