<?php

namespace ChildConnect\CCSoftBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ResponseProposalType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('response','text',array(
				'label' => false,
				'attr' => array(
					'class' => 'response'
				)
			))
			->add('value','text',array(
				'label' => false,
				'required' => true,
				'attr' => array(
					'class' => 'value'
				)
			))
			->add('ordre','number', array(
				'required' => false,
					'attr' => array(
						'class'=> 'ordre',
						'size' => 3,
					)
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
            'data_class' => 'ChildConnect\CCSoftBundle\Entity\ResponseProposal'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'childconnect_ccsoftbundle_responseproposal';
    }
}
