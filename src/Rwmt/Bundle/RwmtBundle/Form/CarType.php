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
            ->add('maker', 'text', array('required'=>'true', 'description'=>'The car manufacturer desired'))
            ->add('model', 'text', array('required'=>'true', 'description'=>'The model of the new car'))
            ->add('color', 'text', array('required'=>'true', 'description'=>'The color of the new car'))
            #->add('isActive')
            ->add('year', 'text', array('required'=>'true', 'description'=>'The year when the car was produced'))
            ->add('licencePlate', 'text', array('required'=>'true', 'description'=>"The car's licence plate"))
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
        return 'Car';
    }
}
