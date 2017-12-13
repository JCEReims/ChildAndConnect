<?php

namespace ChildConnect\CCSoftBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class QuestionType extends AbstractType
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
        $builder
            ->add('name','text',array(
				'label' => 'La question :',
				'attr' => array(
					'class' => 'la-question'
				),
			));
		if($securityContext->isGranted('ROLE_SUPER_ADMIN')) 
            $builder->add('responseType','entity',array(
						  'label'=>'Format de la réponse : ',
						  'class' => 'ChildConnectCCSoftBundle:ResponseType',
						  'property' => 'label', 
						  'multiple' => false,
						  'expanded' => false,
						  'attr' => array(
						  'class' => 'select_type_reponse',
						  ),
						
					  )
			  );
		else if($securityContext->isGranted('ROLE_ADMIN')) 
            $builder->add('responseType','entity',array(
						  'label'=>'Format de la réponse : ',
						  'class' => 'ChildConnectCCSoftBundle:ResponseType',
						  'property' => 'label', 
						  'multiple' => false,
						  'expanded' => false,
						  'attr' => array(
						  'class' => 'select_type_reponse',
						  ),
						 'query_builder' => function(\ChildConnect\CCSoftBundle\Entity\ResponseTypeRepository $er)  {
							return $er->createQueryBuilder('rt')
								->where('rt.id IN (6,7)');
						
							},
					  )
			  );
		  $builder->add('ordre','number', array(
				  'attr' => array(
						'class'=> 'ordre',
						'size' => 3,
					)
		  		)
		 	 )
			 ->add('themeQuestion','entity', array(
			 		'class'=> 'ChildConnectCCSoftBundle:ThemeQuestion',
					'expanded' => false,
					'multiple' => false,
					 'property' => 'name', 
		  		)
		 	 )
		->add('activeStats','checkbox',array(
				'label' => 'Activer pour stats ?',
				'required' => false,
			)		
		)
		->add('activeIntegration','checkbox',array(
				'label' => 'Activer pour la jauge d\'intégration ?',
				'required' => false,
			)		
		)
		->add('responseProposal', 'collection', array(
			   
				  'type' => new ResponseProposalType(),
				  'allow_add' => true,
				  'allow_delete' => true,
				   'prototype' => true,
				  'prototype_name' => '__responseProposal__',
				  'by_reference' => false
				  
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
            'data_class' => 'ChildConnect\CCSoftBundle\Entity\Question'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'childconnect_ccsoftbundle_question';
    }
}
