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
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

class RegistrationFormType extends BaseType
{
   private $securityContext;
   
	public function __construct($class= 'ChildConnect\CCSoftBundle\Entity\User',$securityContext)
	 {
        $this->securityContext = $securityContext;
		parent::__construct($class);
		
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
         $securityContext = $this->securityContext;

		 /*$builder
            ->add('email', 'email', array('label' => 'form.email', 'translation_domain' => 'FOSUserBundle'))
            ->add('username', null, array('label' => 'form.username', 'translation_domain' => 'FOSUserBundle'))
            ->add('plainPassword', 'repeated', array(
                'type' => 'password',
                'options' => array('translation_domain' => 'FOSUserBundle'),
                'first_options' => array('label' => 'form.password'),
                'second_options' => array('label' => 'form.password_confirmation'),
                'invalid_message' => 'fos_user.password.mismatch',
				'required'=>false
            ))
        ;
		*/
		
		
		parent::buildForm($builder, $options);
		$builder
		
			->add('nom','text',array(
			 		'label'=>'Nom :',		 		
			 	)
			 )
			->add('prenom','text',array(
			 		'label'=>'Prénom :',		 		
			 	)
			 )
           ->add('groups','entity',array(
					'label'=> 'Groupe :',
					'class' => 'ChildConnectCCSoftBundle:Group',
					'property' => 'name', 
					'multiple' => true,
					'expanded' => true,
					'required' => true,
					 'query_builder' => function(\ChildConnect\CCSoftBundle\Entity\GroupRepository $er) use($securityContext) {
					 	  $r =$er->createQueryBuilder('g');
						  if(is_object($securityContext) && !$securityContext->isGranted('ROLE_SUPER_ADMIN'))
						  	$r->where('g.id != 4');
							  
						return $r;
					  }, 
				)
				
			)
			->add('association','entity',array(
				'label' => 'Association',
				'class' => 'ChildConnectCCSoftBundle:Association',
				'property' => 'nom', 
				'multiple' => false,
				'expanded' => false,
				'required'=> false,
				'empty_value' => 'Choisir une assoc (si pas Admin Appli)',
				 'query_builder' => function(\ChildConnect\CCSoftBundle\Entity\AssociationRepository $er)  {
						  return $er->createQueryBuilder('a')
							  ->where('a.actif = 1')
							  ->orderBy('a.id','ASC')
							  ;
					  }, 
				)
			)
        ;
    }



    public function getName()
    {
        return 'childconnect_user_registration';
    }
}
