<?php
namespace ChildConnect\CCSoftBundle\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use ChildConnect\CCSoftBundle\Entity\Enfant;
use ChildConnect\CCSoftBundle\Entity\Quiz;
use ChildConnect\CCSoftBundle\Entity\CodeEnfant;
use ChildConnect\CCSoftBundle\Entity\EnfantQuiz;
use ChildConnect\CCSoftBundle\Form\EnfantType;
/**

* Enfant controller.

*

*/
class EnfantController extends FrontController
{
	public function __construct()
	{
		$this->assign('id_body', 'enfant');
		$this->assign('title_page', 'Enfant');
	}
	/**
	
	* Lists all Enfant entities.
	
	*
	
	*/
	public function indexAction(Request $request, $orderBy = 'modifiedAt', $sortBy = 'DESC', $page = 1, $nbrParPage = 12)
	{
		$this->assign('title_H1', 'Liste des enfants');
		$this->assign('classes', array(
			'inner_content_no_border'
		));
		$this->assign('id_body', 'home');
		$em              = $this->getDoctrine()->getManager();
		$securityContext = $this->get('security.context');
		$entities        = $em->getRepository('ChildConnectCCSoftBundle:Enfant')->findEnfants($securityContext, $orderBy, $sortBy, $page, $nbrParPage);
		$enfant_count    = $em->getRepository('ChildConnectCCSoftBundle:Enfant')->findEnfants($securityContext, $orderBy, $sortBy, $page, $nbrParPage, true);
		$template        = 'list-thumb';
		return $this->renderTemplate('ChildConnectCCSoftBundle:Enfant:' . $template . '.html.twig', array(
			'enfants' => $entities,
			'sortBy' => ($sortBy == 'DESC') ? 'ASC' : 'DESC',
			'orderBy' => $orderBy,
			'page' => $page,
			'nombrePage' => (ceil($enfant_count / $nbrParPage) == 0) ? 1 : ceil($enfant_count / $nbrParPage),
			'nbrParPage' => $nbrParPage
		));
	}
	public function indexListAction(Request $request, $orderBy = 'modifiedAt', $sortBy = 'DESC', $page = 1, $nbrParPage = 12)
	{
		$this->assign('classes', array(
			'inner_content_no_border'
		));
		$this->assign('title_H1', 'Liste des enfants');
		$em              = $this->getDoctrine()->getManager();
		$securityContext = $this->get('security.context');
		$entities        = $em->getRepository('ChildConnectCCSoftBundle:Enfant')->findEnfants($securityContext, $orderBy, $sortBy, $page, $nbrParPage);
		$enfant_count    = $em->getRepository('ChildConnectCCSoftBundle:Enfant')->findEnfants($securityContext, $orderBy, $sortBy, $page, $nbrParPage, true);
		$template        = 'index';
		return $this->renderTemplate('ChildConnectCCSoftBundle:Enfant:' . $template . '.html.twig', array(
			'enfants' => $entities,
			'sortBy' => ($sortBy == 'DESC') ? 'ASC' : 'DESC',
			'orderBy' => $orderBy,
			'page' => $page,
			'nombrePage' => (ceil($enfant_count / $nbrParPage) == 0) ? 1 : ceil($enfant_count / $nbrParPage),
			'nbrParPage' => $nbrParPage
		));
	}
	/**
	
	* Creates a new Enfant entity.
	
	*
	
	*/
	public function createAction(Request $request)
	{
		$enfant = new Enfant();
		$user   = $this->getUser();
		$em     = $this->getDoctrine()->getManager();
		$form   = $this->createCreateForm($enfant);
		$form->handleRequest($request);
		$enfant->setActif(1);
		if ($form->isValid()) {
			$em->persist($enfant);
			foreach ($form->get('quiz')->getData() as $q) {
				$quiz       = $em->getRepository('ChildConnectCCSoftBundle:Quiz')->find($q->getId());
				$enfantQuiz = new EnfantQuiz();
				//$enfantQuiz->setDateResponded(new \Datetime);
				$em->persist($enfantQuiz);
				$enfant->addEnfantQuiz($enfantQuiz);
				$quiz->addEnfantQuiz($enfantQuiz);
			}
			$datas      = $this->get('request')->request->get('childconnect_ccsoftbundle_enfant');
			$codeEnfant = new CodeEnfant();
			if (isset($datas['association_base']) && $this->get('security.context')->isGranted('ROLE_ADMIN')) {
				$association = $em->getRepository('ChildConnectCCSoftBundle:Association')->find((int) $datas['association_base']);
				$codeEnfant->setAssociation($association);
			} else
				$codeEnfant->setAssociation($user->getAssociation());
			do {
				//code = substr( "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ", mt_rand(0, 50) , 1) .substr( md5( time() ), 23);
				$code     = rtrim(chunk_split(uniqid(rand()), 5, '-'), '-');
				$lastchar = strrpos($code, '-');
				$code     = substr($code, 0, $lastchar);
			} while ($em->getRepository('ChildConnectCCSoftBundle:CodeEnfant')->codeExist($code));
			$codeEnfant->setCode($code);
			$codeEnfant->setlocked(true);
			$em->persist($codeEnfant);
			$enfant->setCodeEnfant($codeEnfant);
			$uow = $em->getUnitOfWork();
			$uow->computeChangeSets();
			$changeset = $uow->getEntityChangeSet($enfant);
			$em->flush();
			$this->get('logs.insertLog')->insertLog($user, $enfant, 'CREATE', 'ENFANT', $changeset);
			$this->get('session')->getFlashBag()->add('success', 'L\'enfant a été créé');
			$quiz = $em->getRepository('ChildConnectCCSoftBundle:EnfantQuiz')->findOneBy(array(
				'enfant' => $enfant
			), array(
				'id' => 'ASC'
			));
			return $this->redirect($this->generateUrl('quiz_complete', array(
				'quiz' => $quiz->getQuiz()->getId(),
				'enfant' => $enfant->getId()
			)));
			//return $this->redirect($this->generateUrl('enfant_show', array('id' => $enfant->getId())));
		}
		return $this->renderTemplate('ChildConnectCCSoftBundle:Enfant:new.html.twig', array(
			'entity' => $enfant,
			'form' => $form->createView()
		));
	}
	/**
	
	* Creates a form to create a Enfant entity.
	
	*
	
	* @param Enfant $entity The entity
	
	*
	
	* @return \Symfony\Component\Form\Form The form
	
	*/
	private function createCreateForm(Enfant $entity)
	{
		$user            = $this->getUser();
		$securityContext = $this->get('security.context');
		$em              = $this->getDoctrine()->getManager();
		$form            = $this->createForm(new EnfantType(array(
			'user' => $user,
			'securityContext' => $securityContext,
			'er' => $em
		)), $entity, array(
			'action' => $this->generateUrl('enfant_create'),
			'method' => 'POST',
			'attr' => array(
				'class' => 'form_enfant_new form_classic'
			)
		));
		$form->add('submit', 'submit', array(
			'label' => 'Enregistrer'
		));
		return $form;
	}
	/**
	
	* Displays a form to create a new Enfant entity.
	
	*
	
	*/
	public function newAction()
	{
		$securityContext = $this->get('security.context');
		$em              = $this->getDoctrine()->getManager();
		$entity          = new Enfant();
		$this->assign('title_H1', 'Nouvel Enfant');
		$entity->setDateEntree(new \Datetime);
		$entity->setActif(true);
		if (!$securityContext->isGranted('ROLE_ADMIN')) {
			$user        = $this->getUser();
			$association = $em->getRepository('ChildConnectCCSoftBundle:Association')->find($user->getAssociation());
			$entity->addAssociation($association);
		}
		$form = $this->createCreateForm($entity);
		return $this->renderTemplate('ChildConnectCCSoftBundle:Enfant:new.html.twig', array(
			'entity' => $entity,
			'form' => $form->createView(),
			'newEnfant' => true
		));
	}
	/**
	
	* Finds and displays a Enfant entity.
	
	*
	
	*/
	public function showAction($id)
	{
		$em = $this->getDoctrine()->getManager();
		$this->assign('title_H1', 'Identité');
		$this->assign('id_body', 'enfant_show');
		$entity          = $em->getRepository('ChildConnectCCSoftBundle:Enfant')->find($id);
		$themesQuestions = $em->getRepository('ChildConnectCCSoftBundle:ThemeQuestion')->findBy(array(), array(
			'ordre' => 'ASC'
		));
		if (!$entity) {
			throw $this->createNotFoundException('Unable to find Enfant entity.');
		}
		$enfantQuizs = $em->getRepository('ChildConnectCCSoftBundle:EnfantQuiz')->findQuizsByEnfant($entity);
		if (!count($enfantQuizs))
			$enfantQuizs = $em->getRepository('ChildConnectCCSoftBundle:EnfantQuiz')->findQuizsByEnfant($entity, true);
		$quizs = array();
		foreach ($enfantQuizs as $enfantQuiz) {
			$lastQuiz                                       = $em->getRepository('ChildConnectCCSoftBundle:EnfantQuiz')->findLatestEnfantQuizByEnfantAndQuiz($entity, $enfantQuiz->getQuiz());
			$quizs[$enfantQuiz->getQuiz()->getId()]['name'] = $lastQuiz->getQuiz()->getName();
			$responses                                      = $em->getRepository('ChildConnectCCSoftBundle:Response')->findLatestResponseQuizs($lastQuiz);
			$responsesQuizDefault                           = $em->getRepository('ChildConnectCCSoftBundle:ResponseProposal')->getResponsesProposalByQuiz($enfantQuiz->getQuiz());
			$responsesValueDefault                          = 0;
			foreach ($responsesQuizDefault as $responseQuizDefault)
				$responsesValueDefault += (int) $responseQuizDefault['value'];
			$quizs[$enfantQuiz->getQuiz()->getId()]['responsesValueDefault'] = $responsesValueDefault;
			//var_dump($lastQuiz->getId());
			//var_dump(count($responses));
			$responseEnfantValue                                             = 0;
			foreach ($responses as $response) {
				if ($response->getQuestion()->getActiveIntegration())
					$responseEnfantValue += (int) $response->getResponseChoice()->getValue();
			}
			$quizs[$enfantQuiz->getQuiz()->getId()]['responseEnfantValue'] = $responseEnfantValue;
		}
		$deleteForm = $this->createDeleteForm($id);
		$last_photo = $em->getRepository('ChildConnectCCSoftBundle:Photo')->findOneBy(array(
			'enfant' => $entity
		), array(
			'date' => 'DESC'
		));
		return $this->renderTemplate('ChildConnectCCSoftBundle:Enfant:show.html.twig', array(
			'enfant' => $entity,
			'enfantQuizs' => $enfantQuizs,
			'themesQuestions' => $themesQuestions,
			'delete_form' => $deleteForm->createView(),
			'last_photo' => $last_photo,
			'quiz_gauge_insertion' => $quizs
		));
	}
	/**
	
	* Displays a form to edit an existing Enfant entity.
	
	*
	
	*/
	public function editAction($id, Quiz $quiz = NULL)
	{
		$em = $this->getDoctrine()->getManager();
		$this->assign('title_H1', 'Modification de l\'identité');
		$this->assign('id_body', 'enfant_show');
		$entity     = $em->getRepository('ChildConnectCCSoftBundle:Enfant')->find($id);
		$last_photo = $em->getRepository('ChildConnectCCSoftBundle:Photo')->findOneBy(array(
			'enfant' => $entity
		), array(
			'date' => 'DESC'
		));
		if (!$entity) {
			throw $this->createNotFoundException('Unable to find Enfant entity.');
		}
		$editForm   = $this->createEditForm($entity, 'edit');
		$deleteForm = $this->createDeleteForm($id);
		return $this->renderTemplate('ChildConnectCCSoftBundle:Enfant:edit.html.twig', array(
			'enfant' => $entity,
			'quiz' => $quiz,
			'edit_form' => $editForm->createView(),
			'delete_form' => $deleteForm->createView(),
			'last_photo' => $last_photo
		));
	}
	/**
	
	* Creates a form to edit a Enfant entity.
	
	*
	
	* @param Enfant $entity The entity
	
	*
	
	* @return \Symfony\Component\Form\Form The form
	
	*/
	private function createEditForm(Enfant $entity, $action = NULL)
	{
		$user            = $this->getUser();
		$securityContext = $this->get('security.context');
		$em              = $this->getDoctrine()->getManager();
		$form            = $this->createForm(new EnfantType(array(
			'user' => $user,
			'securityContext' => $securityContext,
			$action => true,
			'er' => $em
		)), $entity, array(
			'action' => $this->generateUrl('enfant_update', array(
				'id' => $entity->getId()
			)),
			'method' => 'PUT',
			'attr' => array(
				'class' => 'form_enfant_edit form_classic'
			)
		));
		$form->add('submit', 'submit', array(
			'label' => 'Modifier'
		));
		return $form;
	}
	/**
	
	* Edits an existing Enfant entity.
	
	*
	
	*/
	public function updateAction(Request $request, $id)
	{
		$em     = $this->getDoctrine()->getManager();
		$user   = $this->getUser();
		$enfant = $em->getRepository('ChildConnectCCSoftBundle:Enfant')->find($id);
		if (!$enfant) {
			throw $this->createNotFoundException('Unable to find Enfant entity.');
		}
		foreach ($enfant->getPhoto() as $p) {
			$enfant->removePhoto($p);
			//var_dump($p);
		}
		$deleteForm = $this->createDeleteForm($id);
		$editForm   = $this->createEditForm($enfant, 'update');
		$editForm->handleRequest($request);
		$datas = $this->get('request')->request->get('childconnect_ccsoftbundle_enfant');
		if ($editForm->isValid()) {
			if ($this->get('security.context')->isGranted('ROLE_ADMIN')) {
				$codeEnfant  = $enfant->getCodeEnfant();
				$association = $em->getRepository('ChildConnectCCSoftBundle:Association')->find((int) $datas['association_base']);
				$codeEnfant->setAssociation($association);
				$enfant->setCodeEnfant($codeEnfant);
			}
			$ids_prev_quiz = $em->getRepository('ChildConnectCCSoftBundle:EnfantQuiz')->getQuizs($enfant);
			foreach ($editForm->get('quiz')->getData() as $quiz) {
				if (count($em->getRepository('ChildConnectCCSoftBundle:EnfantQuiz')->findBy(array(
					'quiz' => $quiz,
					'enfant' => $enfant
				))) == 0) {
					$enfantQuiz = new EnfantQuiz();
					//$enfantQuiz->setDateResponded(new \Datetime);
					$enfant->addEnfantQuiz($enfantQuiz);
					$quiz->addEnfantQuiz($enfantQuiz);
					$em->persist($enfantQuiz);
				}
				if (array_key_exists($quiz->getId(), $ids_prev_quiz))
					unset($ids_prev_quiz[$quiz->getId()]);
			}
			if (count($ids_prev_quiz))
				foreach ($ids_prev_quiz as $id_eq) {
					if (count($em->getRepository('ChildConnectCCSoftBundle:Response')->findBy(array(
						'enfantQuiz' => $id_eq
					))) == 0) {
						$enfantQuiz = $em->getRepository('ChildConnectCCSoftBundle:EnfantQuiz')->find($id_eq);
						$em->remove($enfantQuiz);
					}
				}
			$uow = $em->getUnitOfWork();
			$uow->computeChangeSets();
			$changeset = $uow->getEntityChangeSet($enfant);
			$em->flush();
			$this->get('modification_enfant')->modifEnfant($enfant);
			$this->get('logs.insertLog')->insertLog($user, $enfant, 'UPDATE', 'ENFANT', $changeset);
			$this->get('session')->getFlashBag()->add('success', 'l\'Enfant a été mis à jour');
			return $this->redirect($this->generateUrl('enfant_show', array(
				'id' => $id
			)));
		}
		return $this->renderTemplate('ChildConnectCCSoftBundle:Enfant:edit.html.twig', array(
			'entity' => $enfant,
			'edit_form' => $editForm->createView(),
			'delete_form' => $deleteForm->createView()
		));
	}
	/**
	
	* Deletes a Enfant entity.
	
	*
	
	*/
	public function deleteAction(Request $request, $id)
	{
		$form = $this->createDeleteForm($id);
		$form->handleRequest($request);
		if ($form->isValid()) {
			$em     = $this->getDoctrine()->getManager();
			$entity = $em->getRepository('ChildConnectCCSoftBundle:Enfant')->find($id);
			if ($em->getRepository('ChildConnectCCSoftBundle:Enfant')->existResponses($id)) {
				$this->get('session')->getFlashBag()->add('error', 'l\'Enfant a déjà répondu à des questions, il ne peut pas être supprimé');
				return $this->redirect($this->generateUrl('enfant_show', array(
					'id' => $id
				)));
			}
			if (!$entity) {
				throw $this->createNotFoundException('Unable to find Enfant entity.');
			}
			$em->remove($entity);
			$em->flush();
			$this->get('session')->getFlashBag()->add('success', 'Enfant Supprimé');
		}
		return $this->redirect($this->generateUrl('enfant'));
	}
	/**
	
	* Creates a form to delete a Enfant entity by id.
	
	*
	
	* @param mixed $id The entity id
	
	*
	
	* @return \Symfony\Component\Form\Form The form
	
	*/
	private function createDeleteForm($id)
	{
		return $this->createFormBuilder()->setAction($this->generateUrl('enfant_delete', array(
			'id' => $id
		)))->setMethod('DELETE')->add('submit', 'submit', array(
			'label' => 'Supprimer l\'enfant',
			'attr' => array(
				'class' => 'delete_enfant delete_entity'
			)
		))->getForm();
	}
	public function infosEnfantAction(Enfant $enfant)
	{
		$em         = $this->getDoctrine()->getManager();
		$last_photo = $em->getRepository('ChildConnectCCSoftBundle:Photo')->findOneBy(array(
			'enfant' => $enfant
		), array(
			'date' => 'DESC'
		));
		return $this->renderTemplate('ChildConnectCCSoftBundle:Enfant:infos_enfant.html.twig', array(
			'enfant' => $enfant,
			'last_photo' => $last_photo
		));
	}
	public function searchAction(Request $request, $orderBy = 'modifiedAt', $sortBy = 'DESC', $typeShow = 'list', $terms = NULL)
	{
		$this->assign('title_H1', 'Liste des enfants');
		$securityContext = $this->get('security.context');
		$em              = $this->getDoctrine()->getManager();
		if ($request->getMethod() == 'POST') {
			$datas = $request->request->get('childconnect_ccsoftbundle_searchenfanttype');
			if ($datas['s']) {
				$strs  = explode(' ', $datas['s']);
				$terms = urlencode($datas['s']);
			}
		} else if ($request->getMethod() == 'GET') {
			$strs = explode('+', $terms);
		}
		$enfants = $em->getRepository('ChildConnectCCSoftBundle:Enfant')->searchEnfants($strs);
		if ($typeShow == 'list')
			$template = 'list';
		else
			$template = 'thumb';
		return $this->renderTemplate('ChildConnectCCSoftBundle:Enfant:' . $template . '-search.html.twig', array(
			'enfants' => $enfants,
			'terms' => $terms,
			'orderBy' => $orderBy,
			'sortBy' => $sortBy,
			'typeShow' => $typeShow
		));
	}
	public function checkEnfantsExistAjaxAction(Request $request)
	{
		if ($request->isXmlHttpRequest()) {
			$securityContext = $this->get('security.context');
			$em              = $this->getDoctrine()->getManager();
			if ($request->request->get('nom'))
				$strs[] = $request->request->get('nom');
			if ($request->request->get('prenom'))
				$strs[] = $request->request->get('prenom');
			if ($request->request->get('surnom'))
				$strs[] = $request->request->get('surnom');
			$enfants = $em->getRepository('ChildConnectCCSoftBundle:Enfant')->searchEnfants($strs);
			$render  = array();
			if ($enfants) {
				$render['enfants'] = $this->renderView('ChildConnectCCSoftBundle:Enfant:listEnfantsExistAjax.html.twig', array(
					'enfants' => $enfants
				));
				$render['message'] = '<i class="genericon genericon-info" alt="f455"></i>L\'enfant que vous souhaitez ajouter semble similaire à un enfant trouvé dans une autre association';
			}
		}
		$return = json_encode($render);
		return new Response($return, 200, array(
			'Content-Type' => 'application/json'
		));
	}
}