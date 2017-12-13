<?php

namespace ChildConnect\CCSoftBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
Use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
class LieuType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('latitude','number',array(
				'label' => 'Latitude :',
				'attr' => array(
					'class'=> 'latitude_lieu',
					)
			))
            ->add('longitude','number',array(
				'label' => 'Longitude :',
				'attr' => array(
					'class'=> 'longitude_lieu',
				)
			))
          
            ->add('adresse','text',array(
				'label' => 'Adresse et ville :',
				'attr' => array(
					'class'=> 'adresse',
				)
			))
            ->add('commentaire','textarea',array(
				'label' => 'Commentaire :',
				'required' => false,
				'attr' => array(
					
				)
			))
        ;
		$formModifier = function (FormInterface $form,$event) {
        $form ->add('enfant','entity',array(
		   		'label'=>'Enfant ',
				'class' => 'ChildConnectCCSoftBundle:Enfant',
				'property' => 'id', 
				'multiple' => false,
				'expanded' => false,
				'attr' => array(
					'class' => 'hidden',
				),
				'query_builder' => function(\ChildConnect\CCSoftBundle\Entity\EnfantRepository $er)  use ($event) {
							return $er->createQueryBuilder('e')
								//->where('e.actif = 1')
								->andWhere('e = :enfant')
								->setParameter('enfant',$event->getEnfant());
								
						
					},
		   	)
		   );
			
        };
		
		$builder->addEventListener(
            FormEvents::POST_SET_DATA,
            function (FormEvent $event) use ($formModifier) {
                $data = $event->getData();
                $formModifier($event->getForm(),$data);
            }
        );
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ChildConnect\CCSoftBundle\Entity\Lieu'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'childconnect_ccsoftbundle_lieu';
    }
}
