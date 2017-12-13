<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ChildConnect\UserBundle\Controller;
use FOS\UserBundle\Controller\ProfileController as BaseController;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use ChildConnect\CCSoftBundle\Controller\FrontController;
use ChildConnect\UserBundle\Form\Type\ProfileFormType;

/**
 * Controller managing the user profile
 *
 * @author Christophe Coevoet <stof@notk.org>
 */
class ProfileController extends ContainerAware
{
    /**
     * Show the user
     */
    public function showAction()
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }
	$frontcontroller = new FrontController($this->container->get('doctrine')->getManager());
		$frontcontroller->assign('id_body', 'user');
		$frontcontroller->assign('classes',array('inner_content_no_border'));
		$render = $frontcontroller->globalAssign(array(
			'user' => $user,		
            )
		);
		
        return $this->container->get('templating')->renderResponse(
            'ChildConnectUserBundle:Profile:show.html.twig',
           $render
        );
		
    }

    /**
     * Edit the user
     */
    public function editAction(Request $request, $id = NULL)
    {
		// if($id && $this->container->get('security.context')->isGranted('ROLE_ADMIN'))
	   		$user = $this->container->get('fos_user.user_manager')->findUserBy(array('id' => $id));
		//else
	     //  $user = $this->container->get('fos_user.user_manager')->findUserBy(array('id' => $this->container->get('security.context')->getToken()->getUser()));
		   
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->container->get('event_dispatcher');

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        /** @var $formFactory \FOS\UserBundle\Form\Factory\FactoryInterface */
        $formFactory = $this->container->get('fos_user.profile.form.factory');

        //$form = $formFactory->createForm();
		$securityContext = $this->container->get('security.context');
		 $form = $this->container->get('form.factory')->create(new ProfileFormType('ChildConnect\CCSoftBundle\Entity\User', $securityContext));
        $form->setData($user);

        if ('POST' === $request->getMethod()) {
            $form->bind($request);

            if ($form->isValid()) {
                /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
                $userManager = $this->container->get('fos_user.user_manager');

                $event = new FormEvent($form, $request);
                $dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_SUCCESS, $event);

                $userManager->updateUser($user);

                if (null === $response = $event->getResponse()) {
                   $url = $this->container->get('router')->generate('user_edit',array('id' => $id));
                    $response = new RedirectResponse($url);
                }

                $dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_COMPLETED, new FilterUserResponseEvent($user, $request, $response));

                return $response;
            }
        }
		$frontcontroller = new FrontController($this->container->get('doctrine')->getManager());
		$frontcontroller->assign('id_body', 'user');
		$frontcontroller->assign('classes',array('inner_content_no_border'));
		$render = $frontcontroller->globalAssign(array(
			'user' => $user,		
            'form' => $form->createView(),)
		);
		
        return $this->container->get('templating')->renderResponse(
            'ChildConnectUserBundle:Profile:edit.html.twig',
           $render
        );
    }
}
