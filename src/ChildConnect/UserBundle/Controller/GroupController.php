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

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FilterGroupResponseEvent;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseGroupEvent;
use FOS\UserBundle\Event\GroupEvent;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use ChildConnect\CCSoftBundle\Controller\FrontController;
use FOS\UserBundle\Controller\GroupController as BaseController;

/**
 * RESTful controller managing group CRUD
 *
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 * @author Christophe Coevoet <stof@notk.org>
 */
class GroupController extends BaseController
{
    /**
     * Show all groups
     */
    public function listAction()
    {
        $groups = $this->container->get('fos_user.group_manager')->findGroups();
		$frontcontroller = new FrontController($this->container->get('doctrine')->getManager());
		$frontcontroller->assign('id_body', 'group');
		$r = $frontcontroller->globalAssign( array('groups' => $groups));
        return $this->container->get('templating')->renderResponse('ChildConnectUserBundle:Group:list.html.twig', $r);
    }

    /**
     * Show one group
     */
    public function showAction($groupName)
    {
        $group = $this->findGroupBy('name', $groupName);

        return $this->container->get('templating')->renderResponse('FOSUserBundle:Group:show.html.twig', array('group' => $group));
    }

    /**
     * Edit one group, show the edit form
     */
    public function editAction(Request $request, $groupName)
    {
        $group = $this->findGroupBy('name', $groupName);

        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->container->get('event_dispatcher');

        $event = new GetResponseGroupEvent($group, $request);
        $dispatcher->dispatch(FOSUserEvents::GROUP_EDIT_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        /** @var $formFactory \FOS\UserBundle\Form\Factory\FactoryInterface */
        $formFactory = $this->container->get('fos_user.group.form.factory');

        $form = $formFactory->createForm();
        $form->setData($group);

        if ($request->isMethod('POST')) {
            $form->bind($request);

            if ($form->isValid()) {
                /** @var $groupManager \FOS\UserBundle\Model\GroupManagerInterface */
                $groupManager = $this->container->get('fos_user.group_manager');

                $event = new FormEvent($form, $request);
                $dispatcher->dispatch(FOSUserEvents::GROUP_EDIT_SUCCESS, $event);

                $groupManager->updateGroup($group);

                if (null === $response = $event->getResponse()) {
                    //$url = $this->container->get('router')->generate('fos_user_group_show', array('groupName' => $group->getName()));
					$url = $this->container->get('router')->generate('fos_user_group_list');
                    $response = new RedirectResponse($url);
                }

                $dispatcher->dispatch(FOSUserEvents::GROUP_EDIT_COMPLETED, new FilterGroupResponseEvent($group, $request, $response));

                return $response;
            }
        }
		$frontcontroller = new FrontController($this->container->get('doctrine')->getManager());
		$frontcontroller->assign('id_body', 'group');
		$render = $frontcontroller->globalAssign(array(
            	'form' => $form->createView(),
				 'group_name'  => $group->getName(),
			)
		);
		
        return $this->container->get('templating')->renderResponse('ChildConnectUserBundle:Group:edit.html.twig', $render);
    }

    /**
     * Show the new form
     */
    public function newAction(Request $request)
    {
        /** @var $groupManager \FOS\UserBundle\Model\GroupManagerInterface */
        $groupManager = $this->container->get('fos_user.group_manager');
        /** @var $formFactory \FOS\UserBundle\Form\Factory\FactoryInterface */
        $formFactory = $this->container->get('fos_user.group.form.factory');
        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->container->get('event_dispatcher');

        $group = $groupManager->createGroup('');

        $dispatcher->dispatch(FOSUserEvents::GROUP_CREATE_INITIALIZE, new GroupEvent($group, $request));

        $form = $formFactory->createForm();
        $form->setData($group);

        if ($request->isMethod('POST')) {
            $form->bind($request);

            if ($form->isValid()) {
                $event = new FormEvent($form, $request);
                $dispatcher->dispatch(FOSUserEvents::GROUP_CREATE_SUCCESS, $event);

                $groupManager->updateGroup($group);

                if (null === $response = $event->getResponse()) {
                    //$url = $this->container->get('router')->generate('fos_user_group_show', array('groupName' => $group->getName()));
					$url = $this->container->get('router')->generate('fos_user_group_list');
                    $response = new RedirectResponse($url);
                }

                $dispatcher->dispatch(FOSUserEvents::GROUP_CREATE_COMPLETED, new FilterGroupResponseEvent($group, $request, $response));

                return $response;
            }
        }
		$frontcontroller = new FrontController($this->container->get('doctrine')->getManager());
		$frontcontroller->assign('id_body', 'group');
		$render = $frontcontroller->globalAssign(array(
            	'form' => $form->createView(),
				
			)
		);
        return $this->container->get('templating')->renderResponse('ChildConnectUserBundle:Group:new.html.twig', $render);
    }

    /**
     * Delete one group
     */
    public function deleteAction(Request $request, $groupName)
    {
        $group = $this->findGroupBy('name', $groupName);
        $this->container->get('fos_user.group_manager')->deleteGroup($group);

        $response = new RedirectResponse($this->container->get('router')->generate('fos_user_group_list'));

        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->container->get('event_dispatcher');
        $dispatcher->dispatch(FOSUserEvents::GROUP_DELETE_COMPLETED, new FilterGroupResponseEvent($group, $request, $response));

        return $response;
    }

    /**
     * Find a group by a specific property
     *
     * @param string $key   property name
     * @param mixed  $value property value
     *
     * @throws NotFoundHttpException                if user does not exist
     * @return \FOS\UserBundle\Model\GroupInterface
     */
    protected function findGroupBy($key, $value)
    {
        if (!empty($value)) {
            $group = $this->container->get('fos_user.group_manager')->{'findGroupBy'.ucfirst($key)}($value);
        }

        if (empty($group)) {
            throw new NotFoundHttpException(sprintf('The group with "%s" does not exist for value "%s"', $key, $value));
        }

        return $group;
    }

    protected function getEngine()
    {
        return $this->container->getParameter('fos_user.template.engine');
    }
}
