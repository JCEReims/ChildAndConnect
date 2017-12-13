<?php

namespace ChildConnect\CCSoftBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SearchEnfantType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
			->add('s','text',array(
				'label' => false,
				'attr' => array(
					'class'=> 'search-enfant-box',
					'placeholder' => 'Rechercher'
					)
			))
			
          
        ;
    }
    
 

    /**
     * @return string
     */
    public function getName()
    {
        return 'childconnect_ccsoftbundle_searchenfanttype';
    }
}
