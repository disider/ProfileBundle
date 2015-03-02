<?php

namespace Diside\ProfileBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RequestResetPasswordForm extends BaseForm
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username', 'text', array('label' => 'form.username_or_email'));
    }

    public function getName()
    {
        return 'request_reset_password';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver->setDefaults(
            array(
                'validation_groups' => array('request_reset_password'),
            )
        );
    }

}