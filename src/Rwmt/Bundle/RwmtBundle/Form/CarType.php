<?php

namespace Rwmt\Bundle\RwmtBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CarType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            #->add('createdAt')
            #->add('updatedAt')
            ->add('maker')
            ->add('model')
            ->add('color')
            #->add('isActive')
            ->add('year')
            ->add('licencePlate')
            #->add('userId')
            #->add('owner')
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Rwmt\Bundle\RwmtBundle\Entity\Car',
            'csrf_protection'   => false,
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'rwmt_bundle_rwmtbundle_car';
    }
}
