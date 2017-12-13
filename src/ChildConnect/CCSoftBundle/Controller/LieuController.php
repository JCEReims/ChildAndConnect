<?php
namespace ChildConnect\CCSoftBundle\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use ChildConnect\CCSoftBundle\Entity\Lieu;
use ChildConnect\CCSoftBundle\Form\LieuType;
use ChildConnect\CCSoftBundle\Entity\Enfant;
use ChildConnect\CCSoftBundle\Entity\Quiz;
/**

* Lieu controller.

*

*/
class LieuController extends FrontController
{
	public function __construct()
	{
		$this->assign('id_body', 'lieu');
		$this->assign('title_page', 'Lieu');
	}
	/**
	
	* Lists all Lieu entities.
	
	*
	
	*/
	public function indexAction()
	{
		$em       = $this->getDoctrine()->getManager();
		$entities = $em->getRepository('ChildConnectCCSoftBundle:Lieu')->findAll();
		return $this->render('ChildConnectCCSoftBundle:Lieu:index.html.twig', array(
			'entities' => $entities
		));
	}
	/**
	
	* Creates a new Lieu entity.
	
	*
	
	*/
	public function createAction(Request $request, Quiz $quiz = NULL)
	{
		$entity = new Lieu();
		$em     = $this->getDoctrine()->getManager();
		$datas  = $request->request->get('childconnect_ccsoftbundle_lieu');
		$enfant = $em->getRepository('ChildConnectCCSoftBundle:Enfant')->find((int) $datas['enfant']);
		$entity->setEnfant($enfant);
		$form = $this->createCreateForm($entity, $quiz);
		$form->handleRequest($request);
		$user = $this->getUser();
		if ($form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($entity);
			$uow = $em->getUnitOfWork();
			$uow->computeChangeSets();
			$changeset = $uow->getEntityChangeSet($entity);
			$em->flush();
			$this->get('modification_enfant')->modifEnfant($enfant);
			$this->get('logs.insertLog')->insertLog($user, $enfant, 'CREATE', 'LIEU_ENFANT', $changeset);
			$this->get('session')->getFlashBag()->add('success', 'Localisation ajoutée');
			return $this->redirect($this->generateUrl('lieu_new', array(
				'enfant' => $enfant->getId(),
				'quiz' => $quiz->getId()
			)));
		}
		return $this->render('ChildConnectCCSoftBundle:Lieu:new.html.twig', array(
			'entity' => $entity,
			'form' => $form->createView()
		));
	}
	/**
	
	* Creates a form to create a Lieu entity.
	
	*
	
	* @param Lieu $entity The entity
	
	*
	
	* @return \Symfony\Component\Form\Form The form
	
	*/
	private function createCreateForm(Lieu $entity, $quiz = NULL)
	{
		$form = $this->createForm(new LieuType(), $entity, array(
			'action' => $this->generateUrl('lieu_create', array(
				'quiz' => $quiz->getId()
			)),
			'method' => 'POST',
			'attr' => array(
				'class' => 'form_lieu_new form_classic'
			)
		));
		$form->add('submit', 'submit', array(
			'label' => 'Ajouter'
		));
		return $form;
	}
	/**
	
	* Displays a form to create a new Lieu entity.
	
	*
	
	*/
	public function newAction(Enfant $enfant, Quiz $quiz = NULL)
	{
		$this->assign('title_H1', 'Localisation / Map');
		$em     = $this->getDoctrine()->getManager();
		$lieux  = $em->getRepository('ChildConnectCCSoftBundle:Lieu')->findBy(array(
			'enfant' => $enfant
		));
		$entity = new Lieu();
		$entity->setEnfant($enfant);
		$deletesForms = array();
		foreach ($lieux as $lieu) {
			$deletesForms[] = $this->createDeleteForm($lieu->getId(), $quiz)->createView();
		}
		$form = $this->createCreateForm($entity, $quiz);
		return $this->renderTemplate('ChildConnectCCSoftBundle:Lieu:new.html.twig', array(
			'entity' => $entity,
			'form' => $form->createView(),
			'lieux' => $lieux,
			'enfant' => $enfant,
			'quiz' => $quiz,
			'last_photo' => $em->getRepository('ChildConnectCCSoftBundle:Photo')->findOneBy(array(
				'enfant' => $enfant
			), array(
				'date' => 'DESC'
			)),
			'deletesForms' => $deletesForms
		));
	}
	/**
	
	* Finds and displays a Lieu entity.
	
	*
	
	*/
	public function showAction($id)
	{
		$em     = $this->getDoctrine()->getManager();
		$entity = $em->getRepository('ChildConnectCCSoftBundle:Lieu')->find($id);
		if (!$entity) {
			throw $this->createNotFoundException('Unable to find Lieu entity.');
		}
		$deleteForm = $this->createDeleteForm($id);
		return $this->render('ChildConnectCCSoftBundle:Lieu:show.html.twig', array(
			'entity' => $entity,
			'delete_form' => $deleteForm->createView()
		));
	}
	/**
	
	* Displays a form to edit an existing Lieu entity.
	
	*
	
	*/
	public function editAction($id)
	{
		$em     = $this->getDoctrine()->getManager();
		$entity = $em->getRepository('ChildConnectCCSoftBundle:Lieu')->find($id);
		if (!$entity) {
			throw $this->createNotFoundException('Unable to find Lieu entity.');
		}
		$editForm   = $this->createEditForm($entity);
		$deleteForm = $this->createDeleteForm($id);
		return $this->render('ChildConnectCCSoftBundle:Lieu:edit.html.twig', array(
			'entity' => $entity,
			'edit_form' => $editForm->createView(),
			'delete_form' => $deleteForm->createView()
		));
	}
	/**
	
	* Creates a form to edit a Lieu entity.
	
	*
	
	* @param Lieu $entity The entity
	
	*
	
	* @return \Symfony\Component\Form\Form The form
	
	*/
	private function createEditForm(Lieu $entity)
	{
		$form = $this->createForm(new LieuType(), $entity, array(
			'action' => $this->generateUrl('lieu_update', array(
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
	
	* Edits an existing Lieu entity.
	
	*
	
	*/
	public function updateAction(Request $request, $id)
	{
		$em     = $this->getDoctrine()->getManager();
		$entity = $em->getRepository('ChildConnectCCSoftBundle:Lieu')->find($id);
		if (!$entity) {
			throw $this->createNotFoundException('Unable to find Lieu entity.');
		}
		$deleteForm = $this->createDeleteForm($id);
		$editForm   = $this->createEditForm($entity);
		$editForm->handleRequest($request);
		if ($editForm->isValid()) {
			$em->flush();
			return $this->redirect($this->generateUrl('lieu_edit', array(
				'id' => $id
			)));
		}
		return $this->render('ChildConnectCCSoftBundle:Lieu:edit.html.twig', array(
			'entity' => $entity,
			'edit_form' => $editForm->createView(),
			'delete_form' => $deleteForm->createView()
		));
	}
	/**
	
	* Deletes a Lieu entity.
	
	*
	
	*/
	public function deleteAction(Request $request, $id, Quiz $quiz)
	{
		$form = $this->createDeleteForm($id, $quiz);
		$form->handleRequest($request);
		$user = $this->getUser();
		if ($form->isValid()) {
			$em     = $this->getDoctrine()->getManager();
			$entity = $em->getRepository('ChildConnectCCSoftBundle:Lieu')->find($id);
			if (!$entity) {
				throw $this->createNotFoundException('Unable to find Lieu entity.');
			}
			$enfant = $entity->getEnfant();
			$em->remove($entity);
			$em->flush();
			$this->get('logs.insertLog')->insertLog($user, $enfant, 'DELETE', 'LIEU_ENFANT');
			$this->get('session')->getFlashBag()->add('success', 'Localisation supprimée');
		}
		return $this->redirect($this->generateUrl('lieu_new', array(
			'enfant' => $enfant->getId(),
			'quiz' => $quiz->getId()
		)));
	}
	/**
	* Creates a form to delete a Lieu entity by id.
	*
	* @param mixed $id The entity id
	*
	* @return \Symfony\Component\Form\Form The form
	*/
	private function createDeleteForm($id, Quiz $quiz)
	{
		return $this->createFormBuilder()->setAction($this->generateUrl('lieu_delete', array(
			'id' => $id,
			'quiz' => $quiz->getId()
		)))->setMethod('DELETE')->add('submit', 'submit', array(
			'label' => 'Supprimer',
			'attr' => array(
				'class' => 'btn_del_lieu'
			)
		))->getForm();
	}
}

