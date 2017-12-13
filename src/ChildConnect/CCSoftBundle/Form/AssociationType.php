<?php

namespace ChildConnect\CCSoftBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AssociationType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom','text',array(
				'label' => 'Nom :',
			))
            ->add('adresse','textarea',array(
				'label' => 'Adresse :',
			))
            ->add('ville','text',array(
				'label' => 'Ville :',
			))
			 ->add('telephone','text',array(
				'label' => 'Téléphone :',
			))
			 ->add('email','text',array(
				'label' => 'E-mail :',
			))
			 ->add('responsable','text',array(
				'label' => 'Responsable :',
			))
			->add('actif','checkbox',array(
				'label' => 'Actif ?',
				'required' => false
			))
           
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ChildConnect\CCSoftBundle\Entity\Association'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'childconnect_ccsoftbundle_association';
    }
}
