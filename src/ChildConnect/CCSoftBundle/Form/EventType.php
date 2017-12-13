<?php

namespace ChildConnect\CCSoftBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
Use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;

class EventType extends AbstractType
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
            ->add('date','date',array(
						'widget' => 'single_text',
						'format' => 'dd-MM-yyyy',
						'label'=> 'Date :',
						'error_bubbling'=>false,
						'read_only'=> false,
						  'attr' => array(
								'class'=> 'datePicker',
							),
						)
				)
			->add('evenement','textarea',array(
				'label' => 'Description de l\'événement :',
			))
            ->add('commentaire','textarea',array(
				'label' => 'Commentaire :',
				'required' => false
			))
			 ->add('latitude','text',array(
				 'required' => false,
				'attr' => array(
					'class'=> 'latitude_lieu',
					)
			))
            ->add('longitude','text',array(
				'required' => false,
				'attr' => array(
					'class'=> 'longitude_lieu',
				)
			))
          
            ->add('adresse','text',array(
				'required' => false,
				'attr' => array(
					'class'=> 'adresse',
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
		   )
		   ->add('autresEnfants','entity',array(
		   		'label'=>'Sélectionnez d\'autres enfants  avec le même événement :',
				'class' => 'ChildConnectCCSoftBundle:Enfant',
				'property' => 'label', 
				'multiple' => true,
				'expanded' => true,
				'attr' => array(
					'class' => '',
				),
				'mapped'=> false,
				'query_builder' => function(\ChildConnect\CCSoftBundle\Entity\EnfantRepository $er)  use ($event) {
							$currents_assoc = $event->getEnfant()->getAssociations();
							$ids_assoc = array();
							foreach($currents_assoc as $assoc)
								$ids_assoc[] = $assoc->getId();
							$queryBuilder =  $er->createQueryBuilder('e')
								->leftJoin('e.associations', 'a')
								//->where('e.actif = 1')
								->orderBy('e.prenom','ASC')
								->addOrderBy('e.nom','ASC')
								->addOrderBy('e.surnom','ASC')
								->andWhere('e != :enfant')
								->setParameter('enfant',$event->getEnfant())
								;
							$queryBuilder->andWhere($queryBuilder->expr()->in('a.id',$ids_assoc));
							return $queryBuilder;
						
					},
		   	)
		   )
		   ;
			
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
            'data_class' => 'ChildConnect\CCSoftBundle\Entity\Event'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'childconnect_ccsoftbundle_event';
    }
}
