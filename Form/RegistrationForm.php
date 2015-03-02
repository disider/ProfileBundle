<?php

namespace Diside\ProfileBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RegistrationForm extends BaseForm
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', 'email', array('label' => 'form.email'))
            ->add('password', 'password', array(
                'property_path' => 'plainPassword',
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver->setDefaults(array(
            'validation_groups' => array('registration')
        ));
    }

    public function getName()
    {
        return 'user_registration';
    }

}
