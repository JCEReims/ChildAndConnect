<?php
namespace ChildConnect\CCSoftBundle\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
Use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use ChildConnect\CCSoftBundle\Entity\Enfant;
class EnfantType extends AbstractType
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
		$user = $this->options['user'];
		$securityContext = $this->options['securityContext'];
		$builder->add('sexe', 'choice', array(
			'label' => 'Sexe',
			'expanded' => true,
			'choices' => array(
				'F' => 'Féminin',
				'M' => 'Masculin'
			)
		))->add('prenom', 'text', array(
			'label' => 'Prénom :',
			'required' => true
		))->add('nom', 'text', array(
			'label' => 'Nom :',
			'required' => false
		))->add('dateNaissance', 'date', array(
			'widget' => 'single_text',
			'format' => 'dd-MM-yyyy',
			'label' => 'Date de naissance :',
			'error_bubbling' => false,
			'read_only' => true,
			'attr' => array(
				'class' => 'datePicker date_naissance',
				'autocomplete' => 'off'
			)
		))->add('dateEntree', 'date', array(
			'widget' => 'single_text',
			'format' => 'dd-MM-yyyy',
			'label' => 'Date d\'entrée :',
			'error_bubbling' => false,
			'read_only' => false,
			'attr' => array(
				'class' => 'datePicker date_entree'
			)
		))->add('dateSortie', 'date', array(
			'required' => false,
			'widget' => 'single_text',
			'format' => 'dd-MM-yyyy',
			'label' => 'Date de sortie :',
			'error_bubbling' => false,
			'read_only' => false,
			'attr' => array(
				'class' => 'datePicker date_sortie'
			)
		))->add('surnom', 'text', array(
			'label' => 'Surnom :',
			'required' => false
		))->add('signeDistinctif', 'text', array(
			'label' => 'Signes Distinctifs :',
			'required' => false
		))->add('villeOrigine', 'text', array(
			'label' => 'Ville d\'origine :',
			'required' => false
		))->add('paysOrigine', 'text', array(
			'label' => 'Pays d\'origine :',
			'required' => false
		)) /*->add('suiviPar','entity',array(
		
		'class' => 'ChildConnectCCSoftBundle:User',
		
		'query_builder' => function(\ChildConnect\CCSoftBundle\Entity\UserRepository $er) use ($securityContext,$user) {
		
		if($securityContext->isGranted('ROLE_ADMIN'))
		
		return $er->createQueryBuilder('u')
		->innerJoin('u.groups', 'gr')
		->where('gr.id NOT IN (2,3,4)')
		;
		else
		return $er->createQueryBuilder('u')
		->innerJoin('u.association', 'a')
		->innerJoin('u.groups', 'g')
		->where('a.id = :association_id')
		->setParameter('association_id',$user->getAssociation())
		->andWhere('g.id = 1')
		->orderBy('u.nom','ASC');
		},
		
		))*/ ->add('suiviPar', 'text', array(
			'label' => 'Suivi Par :',
			'required' => false
		));
		/*if($securityContext->isGranted('ROLE_ADMIN_ASSOC')) {
		$builder->add('association_base','choices',array(
		'label'=>'Association de base : ',
		'class' => 'ChildConnectCCSoftBundle:Association',
		'property' => 'nom', 
		'multiple' => false,
		'expanded' => false,
		'mapped' => true,
		'required' => true,
		'query_builder' => function(\ChildConnect\CCSoftBundle\Entity\AssociationRepository $er) use ($securityContext,$user) {
		if($securityContext->isGranted('ROLE_ADMIN'))
		return $er->createQueryBuilder('a')
		->where('a.actif = 1')
		->orderBy('a.nom','ASC');
		else
		return $er->createQueryBuilder('a')
		->where('a.actif = 1')
		->andWhere('a.id = :association_id')
		->setParameter('association_id',$user->getAssociation())
		->orderBy('a.nom','ASC');
		},
		)
		);
		}*/
		if ($securityContext->isGranted('ROLE_ADMIN_ASSOC')) {
			$builder->add('association_base', 'choice', array(
				'label' => 'Association de base : ',
				'multiple' => false,
				'expanded' => false,
				'required' => true,
				'choices' => $this->_getAssociations()
			));
		}
		$builder->add('associations', 'entity', array(
			'label' => 'Associations : ',
			'class' => 'ChildConnectCCSoftBundle:Association',
			'property' => 'nom',
			'required' => true,
			'multiple' => true,
			'expanded' => true,
			'attr' => array(
				'class' => 'select_assoc'
			),
			'query_builder' => function(\ChildConnect\CCSoftBundle\Entity\AssociationRepository $er) use ($securityContext, $user)
			{
				if ($securityContext->isGranted('ROLE_ADMIN'))
					return $er->createQueryBuilder('a')->where('a.actif = 1')->orderBy('a.nom', 'ASC');
				else
					return $er->createQueryBuilder('a')->where('a.actif = 1')->andWhere('a.id = :association_id')->setParameter('association_id', $user->getAssociation())->orderBy('a.nom', 'ASC');
			}
		));
		$formModifierQuiz = function(FormInterface $form, Enfant $enfant = null)
		{
			$form->add('quiz', 'entity', array(
				'label' => 'Questionnaire : ',
				'class' => 'ChildConnectCCSoftBundle:Quiz',
				'multiple' => true,
				'expanded' => true,
				'mapped' => false,
				'property' => 'name',
				'required' => true,
				'attr' => array(
					'class' => 'questionnaire'
				),
				'data' => $enfant->getQuizs(),
				'query_builder' => function(\ChildConnect\CCSoftBundle\Entity\QuizRepository $er) use ($enfant)
				{
					$qb = $er->createQueryBuilder('q');
					$ids = array();
					$plop = $qb->select('q.id')->innerJoin('q.enfantQuizs', 'eq')->innerJoin('eq.response', 'r')->innerJoin('eq.enfant', 'e')->where('e.id = :enfant')->having('COUNT(r.id) > 0')->setParameter('enfant', $enfant->getId())->getQuery()->getResult();
					//var_dump( $er->createQueryBuilder('q')->getQuery()->getResult());
					foreach ($plop as $id)
						$ids[] = $id['id'];
					if (count($ids))
						return $er->createQueryBuilder('q')->where($qb->expr()->notIn('q.id', $ids));
					else {
						return $er->createQueryBuilder('q')->select('q');
					}
				}
			));
			return $enfant;
		};
		$builder->addEventListener(FormEvents::POST_SET_DATA, function(FormEvent $event) use ($formModifierQuiz)
		{
			$data = $event->getData();
			$data = $formModifierQuiz($event->getForm(), $data);
			$event->setData($data);
		});
		$formModifierAge = function(FormInterface $form, Enfant $enfant = null)
		{
			$args = array(
				'mapped' => false,
				'label' => 'Age',
				'required' => true,
				'attr' => array(
					'size' => 3,
					'class' => 'case_age'
				)
			);
			if ($enfant->getDateNaissance())
				$args['data'] = (int) date('Y') - (int) $enfant->getDateNaissance()->format('Y');
			$form->add('age', 'text', $args);
			/*if($enfant->getAssociations())
			
			var_dump(count($enfant->getAssociations()));*/
			return $enfant;
		};
		$builder->addEventListener(FormEvents::POST_SET_DATA, function(FormEvent $event) use ($formModifierAge)
		{
			$data = $event->getData();
			$data = $formModifierAge($event->getForm(), $data);
			$event->setData($data);
		});
		$builder->add('photo', 'collection', array(
			'label' => false,
			'mapped' => (isset($this->options['edit'])) ? false : true,
			'required' => false,
			'type' => new PhotoType(),
			'allow_add' => true,
			'allow_delete' => false,
			'prototype' => true,
			'by_reference' => false,
			'prototype_name' => '__photo__'
		))->add('commentaire', 'textarea', array(
			'label' => 'Commentaire :',
			'required' => false
		))->add('actif', 'checkbox', array(
			'label' => ' Activer l\'enfant :',
			'required' => false
		));
	}
	private function _getAssociations()
	{
		$securityContext = $this->options['securityContext'];
		$er = $this->options['er'];
		$user = $this->options['user'];
		$results = array();
		if ($securityContext->isGranted('ROLE_ADMIN'))
			$rs = $er->createQueryBuilder('a')->select('a')->from('ChildConnect\CCSoftBundle\Entity\Association', 'a')->where('a.actif = 1')->orderBy('a.nom', 'ASC')->getQuery()->getResult();
		else
			$rs = $er->createQueryBuilder('a')->select('a')->from('ChildConnect\CCSoftBundle\Entity\Association', 'a')->where('a.actif = 1')->andWhere('a.id = :association_id')->setParameter('association_id', $user->getAssociation())->orderBy('a.nom', 'ASC')->getQuery()->getResult();
		foreach ($rs as $r) {
			$results[$r->getId()] = $r->getNom();
		}
		return $results;
	}
	/**
	
	* @param OptionsResolverInterface $resolver
	
	*/
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'ChildConnect\CCSoftBundle\Entity\Enfant'
		));
	}
	/**
	
	* @return string
	
	*/
	public function getName()
	{
		return 'childconnect_ccsoftbundle_enfant';
	}
}

