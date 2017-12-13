<?php

namespace ChildConnect\CCSoftBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class QuizRemplirType extends QuizType
{
     public function __construct($options = null)
	 {
        $this->options = $options;
    }
	 /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $securityContext = $this->options['securityContext'];
		parent::buildForm($builder, $options);
		
		$builder
           // ->remove('name')
           ->add('question', 'collection', array(
					'type' => new QuestionType( array('securityContext' => $securityContext) ),
					'allow_add' => true,
					'allow_delete' => true,
					 'prototype' => true,
					'prototype_name' => '__question__',
					'by_reference' => false,
					
					)
				 )
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
        return 'childconnect_ccsoftbundle_quizremplir';
    }
}
