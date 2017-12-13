<?php

namespace ChildConnect\CCSoftBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
Use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;

class ExportExcelType extends AbstractType
{
   
     public function __construct($options = null)
	 {
        $this->options = $options;
    }
	
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
     
			 $builder
			/* ->add('association','entity',array(
					'label' => 'Choisir une association :',
					'class' => 'ChildConnectCCSoftBundle:Association',
					'property' => 'nom', 
					'multiple' => false,
					'expanded' => false,
					
					'attr' => array(
					'class' => 'select_association',
					),
					//'empty_value' => 'Sélectionner ...',
					
				)
			)
				->add('dateStart','text',array(
					'mapped' => false,
					'label' => 'Date de début :',
					'attr' => array('class' => 'datePicker'),
					'read_only' => true,
					'data' => date('d-m-Y', strtotime('-1 month'))
				
					)
				)
			->add('dateEnd','text',array(
					'mapped' => false,
					'label' => 'Date de fin :',
					'attr' => array('class' => 'datePicker'),
					'read_only' => true,
					'data' => date('d-m-Y')
					)
				)*/
			->add('submit', 'submit', array('label' => 'Exporter'));
			
		
    }
	
   

    public function getName()
    {
        return 'childconnect_ccsoftbundle_exportexceltype';
    }
}
