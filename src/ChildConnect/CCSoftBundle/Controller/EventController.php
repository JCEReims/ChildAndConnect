<?php
namespace ChildConnect\CCSoftBundle\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use ChildConnect\CCSoftBundle\Entity\Event;
use ChildConnect\CCSoftBundle\Form\EventType;
use ChildConnect\CCSoftBundle\Entity\Enfant;
use ChildConnect\CCSoftBundle\Entity\Quiz;
/**

* Event controller.

*

*/
class EventController extends FrontController
{
	public function __construct()
	{
		$this->assign('id_body', 'event');
		$this->assign('title_page', 'Evénement');
	}
	/**
	
	* Lists all Event entities.
	
	*
	
	*/
	public function indexAction(Quiz $quiz = NULL, Enfant $enfant = NULL)
	{
		$em = $this->getDoctrine()->getManager();
		$this->assign('title_H1', 'Les Evénements');
		if ($enfant)
			$entities = $em->getRepository('ChildConnectCCSoftBundle:Event')->findBy(array(
				'enfant' => $enfant
			));
		else
			$entities = $em->getRepository('ChildConnectCCSoftBundle:Event')->findAll();
		return $this->renderTemplate('ChildConnectCCSoftBundle:Event:index.html.twig', array(
			'entities' => $entities,
			'enfant' => $enfant,
			'quiz' => $quiz,
			'last_photo' => ($enfant) ? $em->getRepository('ChildConnectCCSoftBundle:Photo')->findOneBy(array(
				'enfant' => $enfant
			), array(
				'date' => 'DESC'
			)) : NULL
		));
	}
	public function EventsAllAction()
	{
		$this->assign('id_body', 'all_events');
		$em   = $this->getDoctrine()->getManager();
		$user = $this->getUser();
		$quiz = $em->getRepository('ChildConnectCCSoftBundle:Quiz')->find(1);
		if ($user->getAssociation()) {
			$entities = $em->getRepository('ChildConnectCCSoftBundle:Event')->findEntantByAssocation($user->getAssociation());
		} else {
			$entities = $em->getRepository('ChildConnectCCSoftBundle:Event')->findAll();
		}
		$this->assign('title_H1', 'Tous Les Evénements');
		return $this->renderTemplate('ChildConnectCCSoftBundle:Event:all_events.html.twig', array(
			'entities' => $entities,
			'quiz' => $quiz
		));
	}
	/**
	
	* Creates a new Event entity.
	
	*
	
	*/
	public function createAction(Request $request, Quiz $quiz)
	{
		$entity = new Event();
		$em     = $this->getDoctrine()->getManager();
		$datas  = $request->request->get('childconnect_ccsoftbundle_event');
		$enfant = $em->getRepository('ChildConnectCCSoftBundle:Enfant')->find((int) $datas['enfant']);
		$entity->setEnfant($enfant);
		$form = $this->createCreateForm($entity, $quiz);
		$user = $this->getUser();
		$form->handleRequest($request);
		if ($form->isValid()) {
			$datas = $request->request->get('childconnect_ccsoftbundle_event');
			$em    = $this->getDoctrine()->getManager();
			$em->persist($entity);
			$uow = $em->getUnitOfWork();
			$uow->computeChangeSets();
			$changeset = $uow->getEntityChangeSet($entity);
			$this->get('modification_enfant')->modifEnfant($enfant);
			$this->get('logs.insertLog')->insertLog($user, $enfant, 'CREATE', 'EVENT_ENFANT', $changeset);
			$em->flush();
			if (isset($datas['autresEnfants']))
				foreach ($datas['autresEnfants'] as $autres_enfants_id) {
					$event       = new Event();
					$autreEnfant = $em->getRepository('ChildConnectCCSoftBundle:Enfant')->find((int) $autres_enfants_id);
					$event->setEnfant($autreEnfant);
					if ($datas['adresse'])
						$event->setAdresse($datas['adresse']);
					if ($datas['commentaire'])
						$event->setCommentaire($datas['commentaire']);
					$event->setDate(new \datetime($datas['date']));
					$event->setEvenement($datas['evenement']);
					if ($datas['latitude'])
						$event->setLatitude($datas['latitude']);
					if ($datas['longitude'])
						$event->setLongitude($datas['longitude']);
					$em->persist($event);
					$uow = $em->getUnitOfWork();
					$uow->computeChangeSets();
					$changeset = $uow->getEntityChangeSet($event);
					$this->get('modification_enfant')->modifEnfant($autreEnfant);
					$this->get('logs.insertLog')->insertLog($user, $enfant, 'CREATE', 'EVENT_ENFANT', $changeset);
					$em->flush();
				}
			$this->get('session')->getFlashBag()->add('success', 'Evénement ajouté');
			return $this->redirect($this->generateUrl('event', array(
				'enfant' => $enfant->getId(),
				'quiz' => $quiz->getId()
			)));
		}
		return $this->renderTemplate('ChildConnectCCSoftBundle:Event:new.html.twig', array(
			'entity' => $entity,
			'form' => $form->createView()
		));
	}
	/**
	
	* Creates a form to create a Event entity.
	
	*
	
	* @param Event $entity The entity
	
	*
	
	* @return \Symfony\Component\Form\Form The form
	
	*/
	private function createCreateForm(Event $entity, Quiz $quiz)
	{
		$securityContext = $this->get('security.context');
		$form            = $this->createForm(new EventType(array(
			'securityContext' => $securityContext
		)), $entity, array(
			'action' => $this->generateUrl('event_create', array(
				'quiz' => $quiz->getId()
			)),
			'method' => 'POST',
			'attr' => array(
				'class' => 'form_event_new form_classic'
			)
		));
		$form->add('submit', 'submit', array(
			'label' => 'Créer'
		));
		return $form;
	}
	
	/**
	* Displays a form to create a new Event entity.
	*
	*/
	public function newAction(Enfant $enfant, Quiz $quiz)
	{
		$this->assign('title_H1', 'Nouvel événement');
		$em     = $this->getDoctrine()->getManager();
		$entity = new Event();
		$entity->setEnfant($enfant);
		$entity->setDate(new \Datetime);
		$form = $this->createCreateForm($entity, $quiz);
		return $this->renderTemplate('ChildConnectCCSoftBundle:Event:new.html.twig', array(
			'entity' => $entity,
			'form' => $form->createView(),
			'enfant' => $enfant,
			'quiz' => $quiz,
			'last_photo' => $em->getRepository('ChildConnectCCSoftBundle:Photo')->findOneBy(array(
				'enfant' => $enfant
			), array(
				'date' => 'DESC'
			))
		));
	}
	/**
	* Finds and displays a Event entity.
	*
	*/
	public function showAction($id, Quiz $quiz)
	{
		$em = $this->getDoctrine()->getManager();
		$this->assign('title_H1', 'L\'événement');
		$entity = $em->getRepository('ChildConnectCCSoftBundle:Event')->find($id);
		if (!$entity) {
			throw $this->createNotFoundException('Unable to find Event entity.');
		}
		$deleteForm = $this->createDeleteForm($id,$quiz);
		return $this->renderTemplate('ChildConnectCCSoftBundle:Event:show.html.twig', array(
			'entity' => $entity,
			'quiz' => $quiz,
			'delete_form' => $deleteForm->createView(),
			'enfant' => $entity->getEnfant(),
			'last_photo' => $em->getRepository('ChildConnectCCSoftBundle:Photo')->findOneBy(array(
				'enfant' => $entity->getEnfant()
			), array(
				'date' => 'DESC'
			))
		));
	}
	/**
	* Displays a form to edit an existing Event entity.
	*
	*/
	public function editAction($id, Quiz $quiz = NULL)
	{
		$em = $this->getDoctrine()->getManager();
		$this->assign('title_H1', 'Modifier l\'événement');
		$entity = $em->getRepository('ChildConnectCCSoftBundle:Event')->find($id);
		if (!$entity) {
			throw $this->createNotFoundException('Unable to find Event entity.');
		}
		$editForm   = $this->createEditForm($entity, $quiz);
		$deleteForm = $this->createDeleteForm($id);
		return $this->renderTemplate('ChildConnectCCSoftBundle:Event:edit.html.twig', array(
			'entity' => $entity,
			'quiz' => $quiz,
			'form' => $editForm->createView(),
			// 'delete_form' => $deleteForm->createView(),
			'last_photo' => $em->getRepository('ChildConnectCCSoftBundle:Photo')->findOneBy(array(
				'enfant' => $entity->getEnfant()
			), array(
				'date' => 'DESC'
			)),
			'enfant' => $entity->getEnfant()
		));
	}
	/**
	* Creates a form to edit a Event entity.
	*
	* @param Event $entity The entity
	*
	* @return \Symfony\Component\Form\Form The form
	*/
	private function createEditForm(Event $entity, Quiz $quiz = NULL)
	{
		$form = $this->createForm(new EventType(), $entity, array(
			'action' => $this->generateUrl('event_update', array(
				'id' => $entity->getId(),
				'quiz' => $quiz->getId()
			)),
			'method' => 'PUT',
			'attr' => array(
				'class' => 'form_event_edit form_classic'
			)
		));
		$form->add('submit', 'submit', array(
			'label' => 'Mettre à jour'
		));
		return $form;
	}
	/**
	* Edits an existing Event entity.
	*
	*/
	public function updateAction(Request $request, $id, Quiz $quiz = NULL)
	{
		$em     = $this->getDoctrine()->getManager();
		$user   = $this->getUser();
		$entity = $em->getRepository('ChildConnectCCSoftBundle:Event')->find($id);
		if (!$entity) {
			throw $this->createNotFoundException('Unable to find Event entity.');
		}
		$deleteForm = $this->createDeleteForm($id);
		$editForm   = $this->createEditForm($entity, $quiz);
		$editForm->handleRequest($request);
		if ($editForm->isValid()) {
			$uow = $em->getUnitOfWork();
			$uow->computeChangeSets();
			$changeset = $uow->getEntityChangeSet($entity);
			$em->flush();
			$enfant = $entity->getEnfant();
			$this->get('modification_enfant')->modifEnfant($enfant);
			$this->get('logs.insertLog')->insertLog($user, $entity->getEnfant(), 'UPDATE', 'EVENT_ENFANT', $changeset);
			$this->get('session')->getFlashBag()->add('success', 'Evénement mis à jour');
			return $this->redirect($this->generateUrl('event', array(
				'quiz' => $quiz->getId(),
				'enfant' => $enfant->getId()
			)));
		}
		return $this->render('ChildConnectCCSoftBundle:Event:edit.html.twig', array(
			'entity' => $entity,
			'edit_form' => $editForm->createView(),
			'delete_form' => $deleteForm->createView()
		));
	}
	/**
	* Deletes a Event entity.
	*
	*/
	public function deleteAction(Request $request, $id,Quiz $quiz)
	{
		$form = $this->createDeleteForm($id,$quiz);
		$form->handleRequest($request);
		$user   = $this->getUser();
		
		if ($form->isValid()) {
			$em     = $this->getDoctrine()->getManager();
			$entity = $em->getRepository('ChildConnectCCSoftBundle:Event')->find($id);
			$enfant = $entity->getEnfant();
			if (!$entity) {
				throw $this->createNotFoundException('Unable to find Event entity.');
			}
			$em->remove($entity);
			$em->flush();
			$this->get('logs.insertLog')->insertLog($user, $enfant, 'DELETE', 'EVENT_ENFANT');
			$this->get('session')->getFlashBag()->add('success', 'Evénement supprimé');
		}
		return $this->redirect($this->generateUrl('event', array(
				'enfant' => $enfant->getId(),
				'quiz' => $quiz->getId()
			)));
	}
	/**
	* Creates a form to delete a Event entity by id.
	*
	* @param mixed $id The entity id
	*
	* @return \Symfony\Component\Form\Form The form
	*/
	private function createDeleteForm($id, Quiz $quiz)
	{
		return $this->createFormBuilder()->setAction($this->generateUrl('event_delete', array(
			'id' => $id,
			'quiz' => $quiz->getId()
		)))->setMethod('DELETE')->add('submit', 'submit', array(
			'label' => 'Supprimer',
			'attr' => array(
				'class' => 'delete_enfant delete_entity'
			)
		))->getForm();
	}
}

