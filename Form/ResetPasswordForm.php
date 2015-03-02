<?php

namespace Diside\ProfileBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ResetPasswordForm extends BaseForm
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'password',
            'password',
            array(
                'label' => 'form.password',
                'property_path' => 'plainPassword',
            )
        );
    }

    public function getName()
    {
        return 'reset_password';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver->setDefaults(
            array(
                'validation_groups' => array('reset_password'),
            )
        );
    }

}