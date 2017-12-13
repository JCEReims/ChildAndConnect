<?php

namespace ChildConnect\CCSoftBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SearchCodeBoxType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
			->add('s','text',array(
				'label' => 'Entrer l\'identifiant de l\'enfant :',
				'attr' => array(
					'class'=> 'search-code-box',
					)
			))
			
          
        ;
    }
    
 

    /**
     * @return string
     */
    public function getName()
    {
        return 'childconnect_ccsoftbundle_searchboxtype';
    }
}
