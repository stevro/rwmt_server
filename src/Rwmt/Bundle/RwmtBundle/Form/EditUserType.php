<?php

namespace Rwmt\Bundle\RwmtBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EditUserType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username')
            ->add('email')
            ->add('phone')
            ->add('firstName')
            ->add('lastName')
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Rwmt\Bundle\RwmtBundle\Entity\User',
            'csrf_protection'   => false,
            'validation_groups' => array('Default')
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'rwmt_bundle_rwmtbundle_edit_user';
    }
}
