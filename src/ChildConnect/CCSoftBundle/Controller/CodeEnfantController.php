<?php
namespace ChildConnect\CCSoftBundle\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use ChildConnect\CCSoftBundle\Entity\CodeEnfant;
use ChildConnect\CCSoftBundle\Form\CodeEnfantType;
use ChildConnect\CCSoftBundle\Form\CodeEnfantMultiType;
/**

* CodeEnfant controller.

*

*/
class CodeEnfantController extends FrontController
{
	public function __construct()
	{
		$this->assign('id_body', 'codeenfant');
		$this->assign('title_page', 'Code Enfant');
	}
	/**
	
	* Lists all CodeEnfant entities.
	
	*
	
	*/
	public function indexAction()
	{
		$em       = $this->getDoctrine()->getManager();
		$entities = $em->getRepository('ChildConnectCCSoftBundle:CodeEnfant')->findAll();
		return $this->renderTemplate('ChildConnectCCSoftBundle:CodeEnfant:index.html.twig', array(
			'entities' => $entities
		));
	}
	/**
	
	* Creates a new CodeEnfant entity.
	
	*
	
	*/
	public function createAction(Request $request, $multiple = NULL)
	{
		$entity = new CodeEnfant();
		$user   = $this->getUser();
		$em     = $this->getDoctrine()->getManager();
		if ($user->getAssociation())
			$entity->setAssociation($user->getAssociation());
		if ($multiple)
			$form = $this->createForm(new CodeEnfantMultiType(), $entity, array(
				'action' => $this->generateUrl('codeenfant_create', array(
					'multiple' => true
				)),
				'method' => 'POST'
			));
		else
			$form = $this->createForm(new CodeEnfantType(), $entity, array(
				'action' => $this->generateUrl('codeenfant_create'),
				'method' => 'POST'
			));
		$form->handleRequest($request);
		$numberCodes = $form->get('numberCode')->getData();
		$codesId     = array();
		for ($cpt = 1; $cpt <= $numberCodes; $cpt++) {
			$codeEnfant = new CodeEnfant();
			$codeEnfant->setAssociation($user->getAssociation());
			do {
				//code = substr( "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ", mt_rand(0, 50) , 1) .substr( md5( time() ), 23);
				$code     = rtrim(chunk_split(uniqid(rand()), 5, '-'), '-');
				$lastchar = strrpos($code, '-');
				$code     = substr($code, 0, $lastchar);
			} while ($em->getRepository('ChildConnectCCSoftBundle:CodeEnfant')->codeExist($code));
			$codeEnfant->setCode($code);
			$codeEnfant->setlocked(false);
			$em->persist($codeEnfant);
			$em->flush();
			$codesId[] = $codeEnfant->getId();
			//var_dump(rand ());
		}
		$this->get('session')->getFlashBag()->add('success', 'Configuration mise à jour');
		return $this->redirect($this->generateUrl('codeenfant_showCodes', array(
			'codesId' => implode(',', $codesId)
		)));
	}
	/**
	
	* Creates a form to create a CodeEnfant entity.
	
	*
	
	* @param CodeEnfant $entity The entity
	
	*
	
	* @return \Symfony\Component\Form\Form The form
	
	*/
	private function createCreateForm(CodeEnfant $entity)
	{
		$form = $this->createForm(new CodeEnfantType(), $entity, array(
			'action' => $this->generateUrl('codeenfant_create'),
			'method' => 'POST'
		));
		$form->add('submit', 'submit', array(
			'label' => 'Create'
		));
		return $form;
	}
	/**
	
	* Displays a form to create a new CodeEnfant entity.
	
	*
	
	*/
	public function newAction($multiple = NULL)
	{
		$entity = new CodeEnfant();
		$user   = $this->getUser();
		if ($user->getAssociation())
			$entity->setAssociation($user->getAssociation());
		if ($multiple)
			$form = $this->createForm(new CodeEnfantMultiType(), $entity, array(
				'action' => $this->generateUrl('codeenfant_create', array(
					'multiple' => true
				)),
				'method' => 'POST'
			));
		else
			$form = $this->createForm(new CodeEnfantType(), $entity, array(
				'action' => $this->generateUrl('codeenfant_create'),
				'method' => 'POST'
			));
		$form->add('submit', 'submit', array(
			'label' => 'Create'
		));
		return $this->renderTemplate('ChildConnectCCSoftBundle:CodeEnfant:new.html.twig', array(
			'entity' => $entity,
			'form' => $form->createView()
		));
	}
	/**
	
	* Finds and displays a CodeEnfant entity.
	
	*
	
	*/
	public function showAction($id)
	{
		$em     = $this->getDoctrine()->getManager();
		$entity = $em->getRepository('ChildConnectCCSoftBundle:CodeEnfant')->find($id);
		if (!$entity) {
			throw $this->createNotFoundException('Unable to find CodeEnfant entity.');
		}
		$deleteForm = $this->createDeleteForm($id);
		return $this->render('ChildConnectCCSoftBundle:CodeEnfant:show.html.twig', array(
			'entity' => $entity,
			'delete_form' => $deleteForm->createView()
		));
	}
	public function showCodesAction($codesId)
	{
		$codesId  = explode(',', $codesId);
		//var_dump($codesId);
		$em       = $this->getDoctrine()->getManager();
		$entities = $em->getRepository('ChildConnectCCSoftBundle:CodeEnfant')->findById($codesId);
		return $this->renderTemplate('ChildConnectCCSoftBundle:CodeEnfant:showCodes.html.twig', array(
			'codesId' => $codesId,
			'entities' => $entities
		));
	}
	/**
	
	* Displays a form to edit an existing CodeEnfant entity.
	
	*
	
	*/
	public function editAction($id)
	{
		$em     = $this->getDoctrine()->getManager();
		$entity = $em->getRepository('ChildConnectCCSoftBundle:CodeEnfant')->find($id);
		if (!$entity) {
			throw $this->createNotFoundException('Unable to find CodeEnfant entity.');
		}
		$editForm   = $this->createEditForm($entity);
		$deleteForm = $this->createDeleteForm($id);
		return $this->render('ChildConnectCCSoftBundle:CodeEnfant:edit.html.twig', array(
			'entity' => $entity,
			'edit_form' => $editForm->createView(),
			'delete_form' => $deleteForm->createView()
		));
	}
	/**
	
	* Creates a form to edit a CodeEnfant entity.
	
	*
	
	* @param CodeEnfant $entity The entity
	
	*
	
	* @return \Symfony\Component\Form\Form The form
	
	*/
	private function createEditForm(CodeEnfant $entity)
	{
		$form = $this->createForm(new CodeEnfantType(), $entity, array(
			'action' => $this->generateUrl('codeenfant_update', array(
				'id' => $entity->getId()
			)),
			'method' => 'PUT'
		));
		$form->add('submit', 'submit', array(
			'label' => 'Update'
		));
		return $form;
	}
	/**
	
	* Edits an existing CodeEnfant entity.
	
	*
	
	*/
	public function updateAction(Request $request, $id)
	{
		$em     = $this->getDoctrine()->getManager();
		$entity = $em->getRepository('ChildConnectCCSoftBundle:CodeEnfant')->find($id);
		if (!$entity) {
			throw $this->createNotFoundException('Unable to find CodeEnfant entity.');
		}
		$deleteForm = $this->createDeleteForm($id);
		$editForm   = $this->createEditForm($entity);
		$editForm->handleRequest($request);
		if ($editForm->isValid()) {
			$em->flush();
			return $this->redirect($this->generateUrl('codeenfant_edit', array(
				'id' => $id
			)));
		}
		return $this->render('ChildConnectCCSoftBundle:CodeEnfant:edit.html.twig', array(
			'entity' => $entity,
			'edit_form' => $editForm->createView(),
			'delete_form' => $deleteForm->createView()
		));
	}
	/**
	
	* Deletes a CodeEnfant entity.
	
	*
	
	*/
	public function deleteAction(Request $request, $id)
	{
		$form = $this->createDeleteForm($id);
		$form->handleRequest($request);
		if ($form->isValid()) {
			$em     = $this->getDoctrine()->getManager();
			$entity = $em->getRepository('ChildConnectCCSoftBundle:CodeEnfant')->find($id);
			if (!$entity) {
				throw $this->createNotFoundException('Unable to find CodeEnfant entity.');
			}
			$em->remove($entity);
			$em->flush();
		}
		return $this->redirect($this->generateUrl('codeenfant'));
	}
	/**
	
	* Creates a form to delete a CodeEnfant entity by id.
	
	*
	
	* @param mixed $id The entity id
	
	*
	
	* @return \Symfony\Component\Form\Form The form
	
	*/
	private function createDeleteForm($id)
	{
		return $this->createFormBuilder()->setAction($this->generateUrl('codeenfant_delete', array(
			'id' => $id
		)))->setMethod('DELETE')->add('submit', 'submit', array(
			'label' => 'Delete'
		))->getForm();
	}
	public function checkCodeEnfantAvailableAction(Request $request)
	{
		if ($request->isXmlHttpRequest()) {
			$em             = $this->getDoctrine()->getManager();
			//var_dump($request->request->get('code'));
			$codeEnfant     = $request->request->get('code');
			$render['avai'] = $this->renderView('ChildConnectCCSoftBundle:CodeEnfant:checkCodeEnfantAvailableAjax.html.twig', array());
			$return         = json_encode($render);
			return new Response($return, 200, array(
				'Content-Type' => 'application/json'
			));
		}
	}
}