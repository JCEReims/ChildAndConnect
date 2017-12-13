<?php

namespace ChildConnect\CCSoftBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class QuizType extends AbstractType
{
     /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name','text',array(
				'label' => 'Nom du questionnaire :',
			))
           
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
