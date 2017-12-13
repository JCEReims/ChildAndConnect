<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ChildConnect\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\ProfileFormType as BaseType;


class ProfileFormType extends BaseType
{
    private $securityContext;
   
	public function __construct($class= 'ChildConnect\CCSoftBundle\Entity\User',$securityContext)
	 {
        $this->securityContext = $securityContext;
		parent::__construct($class);
		
	 }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
       
 		//parent::buildForm($builder, $options);
	    $securityContext = $this->securityContext;
		$currentUser =  $this->securityContext->getToken()->getUser();
		$builder
			 ->add('username', null, array('label' => 'form.username', 'translation_domain' => 'FOSUserBundle'))
            ->add('email', 'email', array('label' => 'form.email', 'translation_domain' => 'FOSUserBundle'))
			->add('nom','text',array(
			 		'label'=>'Nom :',		 		
			 	)
			 )
			->add('prenom','text',array(
			 		'label'=>'Prénom :',		 		
			 	)
			 )
           
			->add('association','entity',array(
				'label' => 'Association',
				'class' => 'ChildConnectCCSoftBundle:Association',
				'property' => 'nom', 
				'multiple' => false,
				'expanded' => false,
				'required'=> false,
				'empty_value' => 'choisir une association',
			     'empty_data'  => null,
				 'query_builder' => function(\ChildConnect\CCSoftBundle\Entity\AssociationRepository $er) use($currentUser,$securityContext){
						   $r  = $er->createQueryBuilder('a')
							  ->where('a.actif = 1')
							  ->orderBy('a.id','ASC')
							  ;
							 if(is_object($securityContext) && !$securityContext->isGranted('ROLE_ADMIN') )
							 	$r->where('a = :assoc')->setParameter('assoc' ,$currentUser->getAssociation());
							 return $r;
					  }, 
				)
			)
			 ->add('email', 'email', array('label' => 'form.email', 'translation_domain' => 'FOSUserBundle'))
        ;
		 $builder->add('plainPassword', 'repeated', array(
            'type' => 'password',
            'options' => array('translation_domain' => 'FOSUserBundle'),
            'first_options' => array('label' => 'form.new_password'),
            'second_options' => array('label' => 'form.new_password_confirmation'),
            'invalid_message' => 'fos_user.password.mismatch',
			'required'=>false
        ))
		->add('groups','entity',array(
					'label'=> 'Groupe :',
					'class' => 'ChildConnectCCSoftBundle:Group',
					'property' => 'name', 
					'multiple' => true,
					'expanded' => true,
					'required' => true,
					 'query_builder' => function(\ChildConnect\CCSoftBundle\Entity\GroupRepository $er) use($securityContext) {
						
					 	  $r =$er->createQueryBuilder('g');
						  if(is_object($securityContext) && $securityContext->isGranted('ROLE_ADMIN_ASSOC') && !$securityContext->isGranted('ROLE_ADMIN'))
						  	$r->where('g.id != 4')	->andWhere('g.id != 3')->andWhere('g.id != 2');
						 if(is_object($securityContext) && $securityContext->isGranted('ROLE_ADMIN') && !$securityContext->isGranted('ROLE_SUPER_ADMIN') )
						  	$r->andWhere('g.id != 4');
							  
						return $r;
					  }, 
				)
			)
		->add('enabled', 'checkbox', array(
			'label' => 'Activé :',
			'required' => false,
		))
		;
		
        /*$builder->add('current_password', 'password', array(
            'label' => 'form.current_password',
            'translation_domain' => 'FOSUserBundle',
            'mapped' => false,
            'constraints' => $constraint,
        ));*/
    }


    public function getName()
    {
        return 'childconnect_user_profile';
    }

   
}
