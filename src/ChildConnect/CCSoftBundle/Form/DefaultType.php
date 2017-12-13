<?php

namespace ChildConnect\CCSoftBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DefaultType extends AbstractType
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
				'attr' => array(
					'class'=> 'nom',
					)
			))
			->add('prenom','text',array(
				'label' => 'Prénom :',
				'attr' => array(
					'class'=> 'prenom',
					)
			))
			->add('sujet','text',array(
				'label' => 'Objet :',
				'attr' => array(
					'class'=> 'objet',
					)
			))
            ->add('message','textarea',array(
				'label' => 'Votre Message :',
				'attr' => array(
					'class'=> 'message',
					)
			))
          
        ;
    }
    
 

    /**
     * @return string
     */
    public function getName()
    {
        return 'childconnect_ccsoftbundle_contact';
    }
}
