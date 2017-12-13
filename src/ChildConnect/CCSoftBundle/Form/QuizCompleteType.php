<?php

namespace ChildConnect\CCSoftBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class QuizCompleteType extends AbstractType
{
     public function __construct($options = null) {
        $this->options = $options;
    }


	 /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $opts = $this->options;
		
		/*$builder
            ->add('enfant','entity',array(
					'class' => 'ChildConnectCCSoftBundle:Enfant',
					'property' => 'id', 
					'multiple' => false,
					'expanded' => false,
					'attr' => array(
					'class' => 'enfant',
					)
				)
			)*/
		/*->add('question','collection',array(
				'type' => new QuestionCompleteType(),
				 'prototype' => false,
				 'by_reference' => false,
				
				
				
			)
		)
		*/
           
        ;
    }
	
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ChildConnect\CCSoftBundle\Entity\Quiz'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'childconnect_ccsoftbundle_quiz';
    }
}
