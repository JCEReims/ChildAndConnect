<?php

namespace ChildConnect\CCSoftBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;


class StatistiqueFiltresType extends AbstractType
{
   
     public function __construct($options = null)
	 {
        $this->options = $options;
    }
	
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
           ->add('enfant_actif_inactif','choice',array(
				'mapped' => false,
				'label' => 'Enfant : ',
				'choices' =>  array(0 => 'inactif', 1 => 'Actif'),
				'expanded' => true,
				'attr' => array(
					'class' => 'btn_actif_inactif'
				)
			))
			
			;
    }

   

    public function getName()
    {
        return 'childconnect_ccsoftbundle_statistiquetype';
    }
}
