<?php
namespace ChildConnect\CCSoftBundle\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use ChildConnect\CCSoftBundle\Entity\User;
use ChildConnect\CCSoftBundle\Controller\FrontController;
class UserController extends FrontController
{
	public function __construct()
	{
		$this->assign('id_body', 'user');
		$this->assign('title_page', 'Child & Connect');
	}
	public function indexAction()
	{
		$em = $this->getDoctrine()->getManager();
		$this->assign('title_H1', 'Liste utilisateurs');
		if ($this->get('security.context')->isGranted('ROLE_SUPER_ADMIN'))
			$entities = $em->getRepository('ChildConnectCCSoftBundle:User')->findUsers('all', $this->getUser());
		elseif ($this->get('security.context')->isGranted('ROLE_ADMIN'))
			$entities = $em->getRepository('ChildConnectCCSoftBundle:User')->findUsers('assoc', $this->getUser());
		else
			$entities = $em->getRepository('ChildConnectCCSoftBundle:User')->findUsers('membre', $this->getUser());
		$this->assign('classes', array(
			'inner_content_no_border'
		));
		return $this->renderTemplate('ChildConnectCCSoftBundle:User:index.html.twig', array(
			'entities' => $entities
		));
	}
	/*public function showAction($id)
	
	{
	
	$em = $this->getDoctrine()->getManager();
	
	
	
	$entity = $em->getRepository('ChildConnectCCSoftBundle:User')->find($id);
	
	
	
	if (!$entity) {
	
	throw $this->createNotFoundException('Unable to find User entity.');
	
	}
	
	
	
	$deleteForm = $this->createDeleteForm($id);
	
	
	
	return $this->render('ChildConnectCCSoftBundle:User:show.html.twig', array(
	
	'entity'      => $entity,
	
	'delete_form' => $deleteForm->createView(),        ));
	
	}*/
}

