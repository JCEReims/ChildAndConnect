<?php
namespace ChildConnect\CCSoftBundle\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use ChildConnect\CCSoftBundle\Entity\Association;
use ChildConnect\CCSoftBundle\Form\AssociationType;
/**

* Association controller.

*

*/
class AssociationController extends FrontController
{
	public function __construct()
	{
		$this->assign('id_body', 'association');
		$this->assign('title_page', 'Association');
	}
	/**
	
	* Lists all Association entities.
	
	*
	
	*/
	public function indexAction()
	{
		$em = $this->getDoctrine()->getManager();
		$this->assign('title_H1', 'Les Associations');
		$entities = $em->getRepository('ChildConnectCCSoftBundle:Association')->findAll();
		$this->assign('classes', array(
			'inner_content_no_border'
		));
		return $this->renderTemplate('ChildConnectCCSoftBundle:Association:index.html.twig', array(
			'entities' => $entities
		));
	}
	/**
	
	* Creates a new Association entity.
	
	*
	
	*/
	public function createAction(Request $request)
	{
		$entity = new Association();
		$form   = $this->createCreateForm($entity);
		$form->handleRequest($request);
		if ($form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($entity);
			$em->flush();
			return $this->redirect($this->generateUrl('association_show', array(
				'id' => $entity->getId()
			)));
		}
		return $this->renderTemplate('ChildConnectCCSoftBundle:Association:new.html.twig', array(
			'entity' => $entity,
			'form' => $form->createView()
		));
	}
	/**
	
	* Creates a form to create a Association entity.
	
	*
	
	* @param Association $entity The entity
	
	*
	
	* @return \Symfony\Component\Form\Form The form
	
	*/
	private function createCreateForm(Association $entity)
	{
		$form = $this->createForm(new AssociationType(), $entity, array(
			'action' => $this->generateUrl('association_create'),
			'method' => 'POST',
			'attr' => array(
				'class' => 'form_assoc_new form_classic form_not_expanded'
			)
		));
		$form->add('submit', 'submit', array(
			'label' => 'Créer'
		));
		return $form;
	}
	/**
	
	* Displays a form to create a new Association entity.
	
	*
	
	*/
	public function newAction()
	{
		$this->assign('title_H1', 'Ajout d\'une Association');
		$entity = new Association();
		$form   = $this->createCreateForm($entity);
		return $this->renderTemplate('ChildConnectCCSoftBundle:Association:new.html.twig', array(
			'entity' => $entity,
			'form' => $form->createView()
		));
	}
	/**
	
	* Finds and displays a Association entity.
	
	*
	
	*/
	public function showAction($id)
	{
		$em = $this->getDoctrine()->getManager();
		$this->assign('title_H1', 'L\'Association');
		$entity = $em->getRepository('ChildConnectCCSoftBundle:Association')->find($id);
		if (!$entity) {
			throw $this->createNotFoundException('Unable to find Association entity.');
		}
		$deleteForm = $this->createDeleteForm($id);
		return $this->renderTemplate('ChildConnectCCSoftBundle:Association:show.html.twig', array(
			'entity' => $entity,
			'delete_form' => $deleteForm->createView()
		));
	}
	/**
	
	* Displays a form to edit an existing Association entity.
	
	*
	
	*/
	public function editAction($id)
	{
		$em = $this->getDoctrine()->getManager();
		$this->assign('title_H1', 'Modification de l\'Association');
		$entity = $em->getRepository('ChildConnectCCSoftBundle:Association')->find($id);
		if (!$entity) {
			throw $this->createNotFoundException('Unable to find Association entity.');
		}
		$editForm   = $this->createEditForm($entity);
		$deleteForm = $this->createDeleteForm($id);
		return $this->renderTemplate('ChildConnectCCSoftBundle:Association:edit.html.twig', array(
			'entity' => $entity,
			'edit_form' => $editForm->createView(),
			'delete_form' => $deleteForm->createView()
		));
	}
	/**
	
	* Creates a form to edit a Association entity.
	
	*
	
	* @param Association $entity The entity
	
	*
	
	* @return \Symfony\Component\Form\Form The form
	
	*/
	private function createEditForm(Association $entity)
	{
		$form = $this->createForm(new AssociationType(), $entity, array(
			'action' => $this->generateUrl('association_update', array(
				'id' => $entity->getId()
			)),
			'method' => 'PUT',
			'attr' => array(
				'class' => 'form_assoc_edit form_classic form_not_expanded'
			)
		));
		$form->add('submit', 'submit', array(
			'label' => 'Mettre à jour'
		));
		return $form;
	}
	/**
	* Edits an existing Association entity.
	*
	*/
	public function updateAction(Request $request, $id)
	{
		$em     = $this->getDoctrine()->getManager();
		$entity = $em->getRepository('ChildConnectCCSoftBundle:Association')->find($id);
		if (!$entity) {
			throw $this->createNotFoundException('Unable to find Association entity.');
		}
		$deleteForm = $this->createDeleteForm($id);
		$editForm   = $this->createEditForm($entity);
		$editForm->handleRequest($request);
		if ($editForm->isValid()) {
			$em->flush();
			$this->get('session')->getFlashBag()->add('success', 'Association mise à jour');
			return $this->redirect($this->generateUrl('association_show', array(
				'id' => $id
			)));
		}
		return $this->renderTemplate('ChildConnectCCSoftBundle:Association:edit.html.twig', array(
			'entity' => $entity,
			'edit_form' => $editForm->createView(),
			'delete_form' => $deleteForm->createView()
		));
	}
	/**
	* Deletes a Association entity.
	*
	*/
	public function deleteAction(Request $request, $id)
	{
		$form = $this->createDeleteForm($id);
		$form->handleRequest($request);
		if ($form->isValid()) {
			$em     = $this->getDoctrine()->getManager();
			$entity = $em->getRepository('ChildConnectCCSoftBundle:Association')->find($id);
			if (!$entity) {
				throw $this->createNotFoundException('Unable to find Association entity.');
			}
			$em->remove($entity);
			$em->flush();
		}
		return $this->redirect($this->generateUrl('association'));
	}
	/**
	* Creates a form to delete a Association entity by id.
	*
	* @param mixed $id The entity id
	*
	* @return \Symfony\Component\Form\Form The form
	
	*/
	private function createDeleteForm($id)
	{
		return $this->createFormBuilder()->setAction($this->generateUrl('association_delete', array(
			'id' => $id
		)))->setMethod('DELETE')->add('submit', 'submit', array(
			'label' => 'Supprimer',
			'attr' => array(
				'class' => 'delete_entity'
			)
		))->getForm();
	}
	public function pageAssosciationsAction()
	{
		$em = $this->getDoctrine()->getManager();
		$this->assign('type_page', 'page');
		$this->assign('title_H1', 'Liste des associations');
		$entities = $em->getRepository('ChildConnectCCSoftBundle:Association')->findAll();
		return $this->renderTemplate('ChildConnectCCSoftBundle:Association:page_list_association.html.twig', array(
			'entities' => $entities
		));
	}
}
