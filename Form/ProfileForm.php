<?php

namespace Diside\ProfileBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProfileForm extends BaseForm
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('email', 'text', array('label' => 'form.email'));

        $builder->add('save', 'submit', array('label' => 'form.save'));
        $builder->add(
            'save_and_close',
            'submit',
            array(
                'label' => 'form.save_and_close',
                'button_class' => 'btn btn-default'
            )
        );
    }

    public function getName()
    {
        return 'profile';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver->setDefaults(
            array(
                'validation_groups' => array('edit_profile'),
            )
        );
    }

}

