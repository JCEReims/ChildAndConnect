<?php
namespace ChildConnect\CCSoftBundle\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
class StatistiqueType extends AbstractType
{
	public function __construct($options = null)
	{
		$this->options = $options;
	}
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$user = $this->options['user'];
	   $securityContext = $this->options['securityContext'];
		$builder  ->add('association', 'entity', array(
			'label' => 'Choisir une association :',
			'class' => 'ChildConnectCCSoftBundle:Association',
			'property' => 'nom',
			'multiple' => false,
			'expanded' => false,
			'mapped' => false,
			'attr' => array(
				'class' => 'select_association'
			),
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
			
			//'empty_value' => 'Sélectionner ...',
		));
		$builder->add('question', 'entity', array(
			'label' => 'Choisir une question :',
			'class' => 'ChildConnectCCSoftBundle:Question',
			'property' => 'name',
			'multiple' => false,
			'expanded' => false,
			'mapped' => false,
			'attr' => array(
				'class' => 'select_stats'
			),
			'empty_value' => 'Sélectionner ...',
			'query_builder' => function(\ChildConnect\CCSoftBundle\Entity\QuestionRepository $er)
			{
				return $er->createQueryBuilder('q')->where('q.activeStats = 1');
			}
		));
		if (isset($this->options['filtre_actif_inactif']) && $this->options['filtre_actif_inactif'])
			$builder->add('enfant_actif_inactif', 'choice', array(
				'mapped' => false,
				'label' => 'Enfant : ',
				'choices' => array(
					0 => 'inactif',
					1 => 'Actif'
				),
				'expanded' => true,
				'attr' => array(
					'class' => 'btn_actif_inactif'
				)
			));
		if (isset($this->options['filtre_select_one']) && $this->options['filtre_select_one'])
			$builder->add('select_one', 'choice', array(
				'mapped' => false,
				'label' => '1ère Priorité sur  :',
				'choices' => $this->options['datas_select_priority_one'],
				'expanded' => false,
				'multiple' => false,
				'empty_value' => 'Choisir',
				'attr' => array(
					'class' => 'select_priority'
				)
			));
		if (isset($this->options['filtre_select_two']) && $this->options['filtre_select_two'])
			$builder->add('select_two', 'choice', array(
				'mapped' => false,
				'label' => '2ème Priorité sur  :',
				'choices' => $this->options['datas_select_priority_two'],
				'expanded' => false,
				'multiple' => false,
				'empty_value' => 'Choisir',
				'attr' => array(
					'class' => 'select_priority'
				)
			));
		if (isset($this->options['filtre_select_three']) && $this->options['filtre_select_three'])
			$builder->add('select_three', 'choice', array(
				'mapped' => false,
				'label' => '3ème Priorité sur  :',
				'choices' => $this->options['datas_select_priority_three'],
				'expanded' => false,
				'multiple' => false,
				'empty_value' => 'Choisir',
				'attr' => array(
					'class' => 'select_priority'
				)
			));
		if (isset($this->options['select_pas_age']) && $this->options['select_pas_age'])
			$builder->add('select_pas_age', 'choice', array(
				'mapped' => false,
				'label' => 'Pas des tranches d\'age  :',
				'choices' => array(
					2 => '2 ans',
					4 => '4 ans',
					6 => '6 ans',
					8 => '8 ans'
				),
				'expanded' => false,
				'multiple' => false,
				'empty_value' => 'Sélectionner ...',
				'attr' => array(
					'class' => 'select_priority'
				),
				'required' => true,
			));
		if (isset($this->options['filtre_dates']) && $this->options['filtre_dates']) {
			$builder->add('dateStart', 'text', array(
				'mapped' => false,
				'label' => 'Date de début :',
				//'data' => date('d-m-Y', strtotime('-6 month')),
				'attr' => array(
					'class' => 'datePicker'
				),
				'read_only' => true
			))->add('dateEnd', 'text', array(
				'mapped' => false,
				//'data' =>   date('d-m-Y'),
				'label' => 'Date de fin :',
				'attr' => array(
					'class' => 'datePicker'
				),
				'read_only' => true
			))->add('submit', 'submit', array(
				'label' => 'Ok'
			));
			;
		} //isset($this->options['filtre_dates']) && $this->options['filtre_dates']
	}
	public function getName()
	{
		return 'childconnect_ccsoftbundle_statistiquetype';
	}
}

