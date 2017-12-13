<?php

namespace ChildConnect\UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\GroupFormType as BaseType;

class GroupFormType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        // add your custom field
        $builder
			->add('roles','choice',array(
					'choices'=>array(
					
						'ROLE_USER' => 'UTILSATEUR',
						'ROLE_ADMIN_ASSOC' => 'ADMIN ASSOCIATION',
						'ROLE_ADMIN' => 'ADMIN',
						//'ROLE_SUPER_ADMIN' => 'SUPER ADMIN'
						
					),
					'required'=> true,
					'expanded'=>true,
					'multiple'=>true
			)
		);
    }

     public function getName()
    {
        return 'childconnect_user_group';
    }
}
?>