<?php

namespace Diside\ProfileBundle\Form;

use Diside\ProfileBundle\EventListener\AuthenticationListener;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class LoginForm extends AbstractType
{

    /**
     * @var SessionInterface
     */
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('_username', 'text', array('label' => 'form.username_or_email'));
        $builder->add('_password', 'password', array('label' => 'form.password'));
        $builder->add('_remember_me', 'checkbox', array('label' => 'form.remember_me', 'required' => false));
        $builder->add('_target_path', 'hidden', array('data' => $options['target_path']));

        $builder->add('login', 'submit', array('label' => 'form.login'));

        $builder->addEventSubscriber(new AuthenticationListener($this->session));
    }

    public function getName()
    {
        return null;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'csrf_field_name' => '_csrf_token',
                'intention' => 'authenticate',
                'target_path' => ''
            )
        );
    }

}