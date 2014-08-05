<?php

namespace Rwmt\Bundle\RwmtBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('username', 'text', array('required'=>'true', 'description'=>'The username desired'))
                ->add('email', 'text', array('required'=>'true', 'description'=>'The email of the new user'))
                ->add('phone', 'text', array('required'=>'true', 'description'=>'The password in plain text'))
                ->add('firstName', 'text', array('required'=>'true', 'description'=>'The phone number of the user'))
                ->add('lastName', 'text', array('required'=>'true', 'description'=>'The first name of the user'))
                ->add('rawPassword', 'text', array('required'=>'true', 'description'=>'The last name of the user'))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Rwmt\Bundle\RwmtBundle\Entity\User',
            'csrf_protection' => false,
            'validation_groups' => array('registration', 'Default')
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'RegisterUser';
    }

}