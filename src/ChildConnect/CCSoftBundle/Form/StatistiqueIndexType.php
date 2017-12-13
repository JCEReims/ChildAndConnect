<?php

namespace ChildConnect\CCSoftBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;


class StatistiqueIndexType extends AbstractType
{
   
     public function __construct($options = null)
	 {
        $this->options = $options;
    }
	
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            /*->add('stats','choice',array(
				'mapped' => false,
				'label' => 'Choix des statistiques',
				'choices' =>  $this->options['choices_stats'],
				'empty_value' => 'Choisir une question...',
				'attr' => array(
					'class' => 'select_stats'
				)
			))*/
			->add('question','entity',array(
					'class' => 'ChildConnectCCSoftBundle:Question',
					'property' => 'name', 
					'multiple' => false,
					'expanded' => false,
					'mapped' => false,
					'attr' => array(
					'class' => 'select_stats',
					),
					'empty_value' => 'Choisir une question...',
					'query_builder' => function(\ChildConnect\CCSoftBundle\Entity\QuestionRepository $er)  {
							return $er->createQueryBuilder('q')
								->where('q.activeStats = 1');
					},
				)
			)
			;
    }

   

    public function getName()
    {
        return 'childconnect_ccsoftbundle_statistiquetype';
    }
}
