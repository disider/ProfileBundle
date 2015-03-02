<?php

namespace Diside\ProfileBundle\Form;

use Diside\ProfileBundle\Validator\Constraints\CurrentUserPassword;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ChangePasswordForm extends BaseForm
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'current_password',
            'password',
            array(
                'label' => 'form.current_password',
                'mapped' => false,
                'constraints' => new CurrentUserPassword(array('groups' => 'change_password')),
            )
        );

        $builder->add(
            'new_password',
            'password',
            array(
                'label' => 'form.new_password',
                'property_path' => 'plainPassword'
            )
        );

        $builder->add('save', 'submit', array('label' => 'form.save'));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver->setDefaults(array(
            'validation_groups' => array('change_password')
        ));
    }


    public function getName()
    {
        return 'change_password';
    }


}