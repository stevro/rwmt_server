<?php

namespace Rwmt\Bundle\RwmtBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RideType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fromAddress')
            ->add('toAddress')
            ->add('fromLat')
            ->add('toLat')
            ->add('fromLng')
            ->add('toLng')
            ->add('pickupDateTime', 'datetime', array('widget'=>'single_text', 'invalid_message'=>'The pickup date & time is invalid!'))
            ->add('isRecursive')
            ->add('recursiveDays')
            ->add('totalSeats')
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Rwmt\Bundle\RwmtBundle\Entity\Ride',
            'csrf_protection'   => false,
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'rwmt_bundle_rwmtbundle_ride';
    }
}
