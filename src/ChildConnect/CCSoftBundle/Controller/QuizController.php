<?php
namespace ChildConnect\CCSoftBundle\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JMS\SecurityExtraBundle\Annotation\Secure;
use ChildConnect\CCSoftBundle\Entity\Quiz;
use ChildConnect\CCSoftBundle\Form\QuizType;
use ChildConnect\CCSoftBundle\Form\QuizRemplirType;
use ChildConnect\CCSoftBundle\Form\QuizCompleteType;
use ChildConnect\CCSoftBundle\Entity\Response;
use ChildConnect\CCSoftBundle\Entity\Question;
use ChildConnect\CCSoftBundle\Entity\Enfant;
use ChildConnect\CCSoftBundle\Entity\EnfantQuiz;
use ChildConnect\CCSoftBundle\Entity\ResponseProposal;
/**

* Quiz controller.

*

*/
class QuizController extends FrontController
{
	private $varsForm = array();
	public function __construct()
	{
		$this->assign('id_body', 'quizs');
		$this->assign('title_page', 'Quiz');
	}
	/**
	
	* Lists all Quiz entities.
	
	* @Secure(roles="ROLE_ADMIN")
	
	*/
	public function indexAction()
	{
		$this->assign('title_H1', 'Liste des questionnaires');
		$em       = $this->getDoctrine()->getManager();
		$entities = $em->getRepository('ChildConnectCCSoftBundle:Quiz')->findAll();
		return $this->renderTemplate('ChildConnectCCSoftBundle:Quiz:index.html.twig', array(
			'entities' => $entities
		));
	}
	/**
	
	* Creates a new Quiz entity.
	
	*
	
	*/
	public function createAction(Request $request)
	{
		$entity = new Quiz();
		$form   = $this->createCreateForm($entity);
		$form->handleRequest($request);
		if ($form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($entity);
			$em->flush();
			return $this->redirect($this->generateUrl('quiz_show', array(
				'id' => $entity->getId()
			)));
		}
		return $this->renderTemplate('ChildConnectCCSoftBundle:Quiz:new.html.twig', array(
			'entity' => $entity,
			'form' => $form->createView()
		));
	}
	/**
	
	* Creates a form to create a Quiz entity.
	
	*
	
	* @param Quiz $entity The entity
	
	*
	
	* @return \Symfony\Component\Form\Form The form
	
	*/
	private function createCreateForm(Quiz $entity)
	{
		$form = $this->createForm(new QuizType(), $entity, array(
			'action' => $this->generateUrl('quiz_create'),
			'method' => 'POST',
			'attr' => array(
				'class' => 'form_quiz_new form_classic form_not_expanded'
			)
		));
		$form->add('submit', 'submit', array(
			'label' => 'Create'
		));
		return $form;
	}
	/**
	
	* Displays a form to create a new Quiz entity.
	
	*
	
	*/
	public function newAction()
	{
		$this->assign('title_H1', 'Nouveau questionnaire');
		$entity = new Quiz();
		$form   = $this->createCreateForm($entity);
		return $this->renderTemplate('ChildConnectCCSoftBundle:Quiz:new.html.twig', array(
			'entity' => $entity,
			'form' => $form->createView()
		));
	}
	/**
	
	* Finds and displays a Quiz entity.
	
	*
	
	*/
	public function showAction($id)
	{
		$em     = $this->getDoctrine()->getManager();
		$entity = $em->getRepository('ChildConnectCCSoftBundle:Quiz')->find($id);
		if (!$entity) {
			throw $this->createNotFoundException('Unable to find Quiz entity.');
		}
		$deleteForm = $this->createDeleteForm($id);
		return $this->renderTemplate('ChildConnectCCSoftBundle:Quiz:show.html.twig', array(
			'entity' => $entity,
			'delete_form' => $deleteForm->createView()
		));
	}
	/**
	
	* Displays a form to edit an existing Quiz entity.
	
	*
	
	*/
	public function editAction($id)
	{
		$em = $this->getDoctrine()->getManager();
		$this->assign('title_H1', 'Modification questionnaire');
		$entity = $em->getRepository('ChildConnectCCSoftBundle:Quiz')->find($id);
		if (!$entity) {
			throw $this->createNotFoundException('Unable to find Quiz entity.');
		}
		$editForm   = $this->createEditForm($entity);
		$deleteForm = $this->createDeleteForm($id);
		return $this->renderTemplate('ChildConnectCCSoftBundle:Quiz:edit.html.twig', array(
			'entity' => $entity,
			'edit_form' => $editForm->createView(),
			'delete_form' => $deleteForm->createView()
		));
	}
	/**
	
	* Creates a form to edit a Quiz entity.
	
	*
	
	* @param Quiz $entity The entity
	
	*
	
	* @return \Symfony\Component\Form\Form The form
	
	*/
	private function createEditForm(Quiz $entity)
	{
		$form = $this->createForm(new QuizType(), $entity, array(
			'action' => $this->generateUrl('quiz_update', array(
				'id' => $entity->getId()
			)),
			'method' => 'PUT',
			'attr' => array(
				'class' => 'form_quiz_edit form_classic form_not_expanded'
			)
		));
		$form->add('submit', 'submit', array(
			'label' => 'Mettre à jour'
		));
		return $form;
	}
	/**
	
	* Edits an existing Quiz entity.
	
	*
	
	*/
	public function updateAction(Request $request, $id)
	{
		$em     = $this->getDoctrine()->getManager();
		$entity = $em->getRepository('ChildConnectCCSoftBundle:Quiz')->find($id);
		if (!$entity) {
			throw $this->createNotFoundException('Unable to find Quiz entity.');
		}
		$deleteForm = $this->createDeleteForm($id);
		$editForm   = $this->createEditForm($entity);
		$editForm->handleRequest($request);
		if ($editForm->isValid()) {
			$em->flush();
			return $this->redirect($this->generateUrl('quiz_edit', array(
				'id' => $id
			)));
		}
		return $this->renderTemplate('ChildConnectCCSoftBundle:Quiz:edit.html.twig', array(
			'entity' => $entity,
			'edit_form' => $editForm->createView(),
			'delete_form' => $deleteForm->createView()
		));
	}
	/**
	
	* Deletes a Quiz entity.
	
	*
	
	*/
	public function deleteAction(Request $request, $id)
	{
		$form = $this->createDeleteForm($id);
		$form->handleRequest($request);
		if ($form->isValid()) {
			$em     = $this->getDoctrine()->getManager();
			$entity = $em->getRepository('ChildConnectCCSoftBundle:Quiz')->find($id);
			if (!$entity) {
				throw $this->createNotFoundException('Unable to find Quiz entity.');
			}
			$em->remove($entity);
			$em->flush();
		}
		return $this->redirect($this->generateUrl('quiz'));
	}
	public function remplirAction($id)
	{
		$em     = $this->getDoctrine()->getManager();
		$entity = $em->getRepository('ChildConnectCCSoftBundle:Quiz')->find($id);
		if (!$entity) {
			throw $this->createNotFoundException('Unable to find Quiz entity.');
		}
		$this->assign('title_H1', 'Questionnaire : ' . $entity->getName());
		$form = $this->createForm(
                    new QuizRemplirType(array(
                            'securityContext' => $this->get('security.context')
                        )
                    ),
                    $entity,
                    array(
			'action' => $this->generateUrl('quiz_create_remplir', array(
				'id' => $id
			)),
			'method' => 'POST',
			'attr' => array(
				'class' => 'form_remplir_quiz'
			)
		    )
                );
		$form->add('submit', 'submit', array(
			'label' => 'Enregistrer'
		));
                
                $formView = $form->createView();
                
                $questions = $entity->getQuestion()->toArray();
                
                usort($questions, function($item1, $item2) {
                    if ($item1->getThemeQuestion()->getOrdre() != $item2->getThemeQuestion()->getOrdre()) {
                        return $item1->getThemeQuestion()->getOrdre() < $item2->getThemeQuestion()->getOrdre() ? -1 : 1;;
                    }
                    if ($item1->getOrdre() == $item2->getOrdre()) return 0;
                    return $item1->getOrdre() < $item2->getOrdre() ? -1 : 1;
                });
                
		return $this->renderTemplate('ChildConnectCCSoftBundle:Quiz:remplir-quiz.html.twig', array(
                    'entity' => $entity,
                    'form' => $formView
		));
	}
	public function remplirCreateAction(Request $request, $id)
	{
		$entity = new Quiz();
		$em     = $this->getDoctrine()->getManager();
		$entity = $em->getRepository('ChildConnectCCSoftBundle:Quiz')->find($id);
		$form   = $this->createForm(new QuizRemplirType(array(
			'securityContext' => $this->get('security.context')
		)), $entity, array(
			'action' => $this->generateUrl('quiz_create_remplir', array(
				'id' => $id
			)),
			'method' => 'POST'
		));
		$form->add('submit', 'submit', array(
			'label' => 'Enregistrer'
		));
		$form->handleRequest($request);
		if ($form->isValid()) {
			$em->persist($entity);
			$em->flush();
			$this->get('session')->getFlashBag()->add('success', 'le questionnaire a été mis à jour');
			return $this->redirect($this->generateUrl('quiz_remplir', array(
				'id' => $id
			)));
		}
		return $this->renderTemplate('ChildConnectCCSoftBundle:Quiz:remplir-quiz.html.twig', array(
			'entity' => $entity,
			'form' => $form->createView()
		));
	}
	public function quizCompleteAction(Quiz $quiz, Enfant $enfant)
	{
		$em              = $this->getDoctrine()->getManager();
		$themesQuestions = $em->getRepository('ChildConnectCCSoftBundle:ThemeQuestion')->findBy(array(), array(
			'ordre' => 'ASC'
		));
		$enfantQuiz      = $em->getRepository('ChildConnectCCSoftBundle:EnfantQuiz')->findOneBy(array(
			'enfant' => $enfant,
			'quiz' => $quiz
		), array(
			'id' => 'DESC'
		));
		if (count($enfantQuiz->getResponse()))
			$form = $this->editCompleteForm($quiz, $enfant, $enfantQuiz);
		else
			$form = $this->createCompleteForm($quiz, $enfant);
		return $this->renderTemplate('ChildConnectCCSoftBundle:Quiz:repondre-questionnaire.html.twig', array(
			'form' => $form->createView(),
			'enfant' => $enfant,
			'quiz' => $quiz,
			'last_photo' => $em->getRepository('ChildConnectCCSoftBundle:Photo')->findOneBy(array(
				'enfant' => $enfant
			), array(
				'date' => 'DESC'
			)),
			'themes' => $em->getRepository('ChildConnectCCSoftBundle:ThemeQuestion')->findBy(array(), array(
				'ordre' => 'ASC'
			))
		));
	}
	private function createCompleteForm(Quiz $quiz, Enfant $enfant)
	{
		$em                       = $this->getDoctrine()->getManager();
		$questions                = $em->getRepository('ChildConnectCCSoftBundle:Question')->getQuestions($quiz);
		$formBuilderQuestionnaire = $this->get('form.factory')->createNamedBuilder('childconnect_ccsoftbundle_quiz', 'form', $quiz, array(
			'action' => $this->generateUrl('quiz_complete_post', array(
				'id' => $quiz->getId()
			)),
			'method' => 'POST'
		));
		$formBuilderQuestionnaire->add('quiz_id', 'hidden', array(
			'label' => false,
			'mapped' => false,
			'data' => $quiz->getId()
		))->add('enfant_id', 'hidden', array(
			'label' => false,
			'mapped' => false,
			'data' => $enfant->getId()
		));
		$formBuilderQuestions = $this->get('form.factory')->createNamedBuilder('question', 'form', new Question(), array(
			'mapped' => false,
			'required' => false
		));
		foreach ($questions as $question) {
			//var_dump($question->getName() . ' - '. $question->getThemeQuestion()->getName() . ' - ' . $question->getOrdre());
			$answer = new Response();
			$answer->setQuestion($question);
			$formBuilder = $this->get('form.factory')->createNamedBuilder($question->getId(), 'form', $answer, array(
				'mapped' => false,
				'required' => false
			));
			switch ($question->getResponseType()->getTypeField()) {
				case 'text':
					$formBuilder->add('responseText', 'text', array(
						'required' => false,
						'label' => $question->getName()
					));
					break;
				case 'textarea':
					$formBuilder->add('responseTextarea', 'textarea', array(
						'required' => false,
						'label' => $question->getName()
					));
					break;
				case 'select':
					$choices           = array();
					$responseProposals = $question->getResponseProposal();
					foreach ($responseProposals as $responseProposal) {
						$choices[$responseProposal->getId()] = $responseProposal->getResponse();
					}
					$formBuilder->add('responseChoice', 'entity', array(
						'required' => false,
						'label' => $question->getName(),
						'class' => 'ChildConnectCCSoftBundle:ResponseProposal',
						'expanded' => false,
						'multiple' => false,
						'property' => 'response',
						'empty_value' => false,
						'query_builder' => function(\ChildConnect\CCSoftBundle\Entity\ResponseProposalRepository $er) use ($question)
						{
							return $er->createQueryBuilder('rp')->where('rp.question = :question')->setParameter('question', $question);
						}
					));
					break;
				case 'radio':
					$choices           = array();
					$responseProposals = $question->getResponseProposal();
					foreach ($responseProposals as $responseProposal) {
						$choices[$responseProposal->getId()] = $responseProposal->getResponse();
					}
					$formBuilder->add('responseChoice', 'entity', array(
						'required' => false,
						'label' => $question->getName(),
						'class' => 'ChildConnectCCSoftBundle:ResponseProposal',
						'expanded' => true,
						'multiple' => false,
						'property' => 'response',
						'empty_value' => false,
						'query_builder' => function(\ChildConnect\CCSoftBundle\Entity\ResponseProposalRepository $er) use ($question)
						{
							return $er->createQueryBuilder('rp')->where('rp.question = :question')->setParameter('question', $question);
						}
					));
					break;
			}
			$formBuilder->add('commentaire', 'textarea', array(
				'required' => false,
				'label' => 'Commentaire',
				'attr' => array(
				//'class' => 'commentaire'
				)
			));
			$formBuilderQuestions->add($formBuilder);
		}
		$formBuilderQuestionnaire->add($formBuilderQuestions);
		$formBuilderQuestionnaire->add('submit', 'submit', array(
			'label' => 'Valider'
		));
		$form = $formBuilderQuestionnaire->getForm();
		return $form;
	}
	public function quizCompletePostAction(Request $request, $id)
	{
		$datas     = $this->get('request')->request->get('childconnect_ccsoftbundle_quiz');
		$em        = $this->getDoctrine()->getManager();
		$quiz      = $em->getRepository('ChildConnectCCSoftBundle:Quiz')->find($id);
		$questions = $em->getRepository('ChildConnectCCSoftBundle:Question')->getQuestions($quiz);
		$enfant    = $em->getRepository('ChildConnectCCSoftBundle:Enfant')->find((int) $datas['enfant_id']);
		$form      = $this->createCompleteForm($quiz, $enfant);
		$user      = $this->getUser();
		$form->handleRequest($request);
		if ($form->isValid()) {
			$newEnfant  = $em->getRepository('ChildConnectCCSoftBundle:Enfant')->isNewEnfant($enfant);
			$enfantQuiz = new EnfantQuiz();
			$enfantQuiz->setQuiz($quiz);
			$enfantQuiz->setEnfant($enfant);
			$enfantQuiz->setDateResponded(new \Datetime);
			$em->persist($enfantQuiz);
			$em->flush();
			foreach ($questions as $question) {
				$responses = $datas['question'][$question->getId()];
				foreach ($responses as $typeResponse => $response) {
					if ($response) {
						$answer = new Response();
						$answer->setDateResponse(new \Datetime);
						$answer->setQuestion($question);
						$answer->setEnfantQuiz($enfantQuiz);
						switch ($typeResponse) {
							case 'responseText':
								$a = '$answer->set' . ucfirst($typeResponse) . '("' . $response . '");';
								eval($a);
								break;
							case 'responseTextarea':
								$a = '$answer->set' . ucfirst($typeResponse) . '("' . $response . '");';
								eval($a);
								break;
							case 'responseChoice':
								$rp = $em->getRepository('ChildConnectCCSoftBundle:ResponseProposal')->find((int) $response);
								$answer->setResponseChoice($rp);
								break;
							case 'commentaire':
								if (!empty($response))
									$answer->setCommentaire($response);
								break;
						}
						$em->persist($answer);
						$em->flush();
					}
				}
			}
			$this->get('logs.insertLog')->insertLog($user, $enfant, 'UPDATE', 'REPONSES_ENFANT');
			$this->get('session')->getFlashBag()->add('success', 'Réponses mises à jour');
			if ($newEnfant)
				return $this->redirect($this->generateUrl('event_new', array(
					'quiz' => $quiz->getId(),
					'enfant' => $enfant->getId()
				)));
			else
				return $this->redirect($this->generateUrl('enfant_show', array(
					'id' => $enfant->getId()
				)));
		}
	}
	private function editCompleteForm(Quiz $quiz, Enfant $enfant, EnfantQuiz $enfantQuiz)
	{
		$em = $this->getDoctrine()->getManager();
		$this->assign('title_H1', 'Remplir le questionnaire');
		$questions                = $em->getRepository('ChildConnectCCSoftBundle:Question')->getQuestions($quiz);
		/* foreach ($questions as $question) {
		
		var_dump($question->getName() . ' - '. $question->getThemeQuestion()->getName() . ' - ' . $question->getOrdre());
		
		}*/
		$formBuilderQuestionnaire = $this->get('form.factory')->createNamedBuilder('childconnect_ccsoftbundle_quiz', 'form', $quiz, array(
			'action' => $this->generateUrl('quiz_complete_post', array(
				'id' => $quiz->getId()
			)),
			'method' => 'POST'
		));
		$formBuilderQuestionnaire->add('quiz_id', 'hidden', array(
			'label' => false,
			'mapped' => false,
			'data' => $quiz->getId()
		))->add('enfant_id', 'hidden', array(
			'label' => false,
			'mapped' => false,
			'data' => $enfant->getId()
		));
		$formBuilderQuestions = $this->get('form.factory')->createNamedBuilder('question', 'form', new Question(), array(
			'mapped' => false,
			'required' => false
		));
		foreach ($questions as $question) {
			$oldResponses = $em->getRepository('ChildConnectCCSoftBundle:Response')->findBy(array(
				'enfantQuiz' => $enfantQuiz,
				'question' => $question
			));
			//var_dump($oldResponses);
			/*foreach($oldResponses as $key => &$oldResponse) {
			
			var_dump($oldResponse);
			
			}
			
			var_dump('=========================================================');
			
			*/
			$answer       = new Response();
			$answer->setQuestion($question);
			$formBuilder = $this->get('form.factory')->createNamedBuilder($question->getId(), 'form', $answer, array(
				'mapped' => false,
				'required' => false
			));
			if ($oldResponses) {
				foreach ($oldResponses as $oldResponse) {
					if ($oldResponse->getCommentaire() == NULL) {
						switch ($question->getResponseType()->getTypeField()) {
							case 'text':
								$formBuilder->add('responseText', 'text', array(
									'required' => false,
									'label' => $question->getName(),
									'data' => (($oldResponse->getResponseText()) ? $oldResponse->getResponseText() : NULL)
								));
								break;
							case 'textarea':
								$formBuilder->add('responseTextarea', 'textarea', array(
									'required' => false,
									'label' => $question->getName(),
									'data' => (($oldResponse->getResponseTextarea()) ? $oldResponse->getResponseTextarea() : NULL)
								));
								break;
							case 'select':
								$formBuilder->add('responseChoice', 'entity', array(
									'required' => false,
									'label' => $question->getName(),
									'class' => 'ChildConnectCCSoftBundle:ResponseProposal',
									'expanded' => false,
									'multiple' => false,
									'property' => 'response',
									'empty_value' => false,
									'query_builder' => function(\ChildConnect\CCSoftBundle\Entity\ResponseProposalRepository $er) use ($question)
									{
										return $er->createQueryBuilder('rp')->where('rp.question = :question')->setParameter('question', $question);
									},
									'data' => (($oldResponse->getResponseChoice()) ? $oldResponse->getResponseChoice() : NULL)
								));
								break;
							case 'radio':
								$formBuilder->add('responseChoice', 'entity', array(
									'required' => false,
									'label' => $question->getName(),
									'class' => 'ChildConnectCCSoftBundle:ResponseProposal',
									'expanded' => true,
									'multiple' => false,
									'property' => 'response',
									'empty_value' => false,
									'query_builder' => function(\ChildConnect\CCSoftBundle\Entity\ResponseProposalRepository $er) use ($question)
									{
										return $er->createQueryBuilder('rp')->where('rp.question = :question')->setParameter('question', $question);
									},
									'data' => (($oldResponse->getResponseChoice()) ? $oldResponse->getResponseChoice() : NULL)
								));
								break;
						}
					}
					$formBuilder->add('commentaire', 'textarea', array(
						'required' => false,
						'label' => 'Commentaire',
						'attr' => array(
							'class' => 'commentaire'
						),
						'data' => (($oldResponse->getCommentaire()) ? $oldResponse->getCommentaire() : NULL)
					));
				} // endoreach
			} else {
				switch ($question->getResponseType()->getTypeField()) {
					case 'text':
						$formBuilder->add('responseText', 'text', array(
							'required' => false,
							'label' => $question->getName()
						));
						break;
					case 'textarea':
						$formBuilder->add('responseTextarea', 'textarea', array(
							'required' => false,
							'label' => $question->getName()
						));
						break;
					case 'select':
						$choices           = array();
						$responseProposals = $question->getResponseProposal();
						foreach ($responseProposals as $responseProposal) {
							$choices[$responseProposal->getId()] = $responseProposal->getResponse();
						}
						$formBuilder->add('responseChoice', 'entity', array(
							'required' => false,
							'label' => $question->getName(),
							'class' => 'ChildConnectCCSoftBundle:ResponseProposal',
							'expanded' => false,
							'multiple' => false,
							'property' => 'response',
							'empty_value' => false,
							'query_builder' => function(\ChildConnect\CCSoftBundle\Entity\ResponseProposalRepository $er) use ($question)
							{
								return $er->createQueryBuilder('rp')->where('rp.question = :question')->setParameter('question', $question);
							}
						));
						break;
					case 'radio':
						$choices           = array();
						$responseProposals = $question->getResponseProposal();
						foreach ($responseProposals as $responseProposal) {
							$choices[$responseProposal->getId()] = $responseProposal->getResponse();
						}
						$formBuilder->add('responseChoice', 'entity', array(
							'required' => false,
							'label' => $question->getName(),
							'class' => 'ChildConnectCCSoftBundle:ResponseProposal',
							'expanded' => true,
							'multiple' => false,
							'property' => 'response',
							'empty_value' => false,
							'query_builder' => function(\ChildConnect\CCSoftBundle\Entity\ResponseProposalRepository $er) use ($question)
							{
								return $er->createQueryBuilder('rp')->where('rp.question = :question')->setParameter('question', $question);
							}
						));
						break;
				}
				$formBuilder->add('commentaire', 'textarea', array(
					'required' => false,
					'label' => 'Commentaire',
					'attr' => array(
						'class' => 'commentaire'
					)
				));
			} // endif
			$formBuilderQuestions->add($formBuilder);
		}
		$formBuilderQuestionnaire->add($formBuilderQuestions);
		$formBuilderQuestionnaire->add('submit', 'submit', array(
			'label' => 'Enregistrer les réponses'
		));
		$form = $formBuilderQuestionnaire->getForm();
		$a    = array_values($oldResponses);
		$this->assign('title_H1', $questions[0]->getThemeQuestion()->getName());
		return $form;
	}
	public function quizShowResponseAction(Quiz $quiz, Enfant $enfant)
	{
		$this->assign('id_body', 'evolution');
		$em         = $this->getDoctrine()->getManager();
		$enfantQuiz = $em->getRepository('ChildConnectCCSoftBundle:EnfantQuiz')->findOneBy(array(
			'enfant' => $enfant,
			'quiz' => $quiz
		), array(
			'id' => 'DESC'
		));
		$questions  = $em->getRepository('ChildConnectCCSoftBundle:Question')->getQuestions($quiz, $enfant, $enfantQuiz);
		$answers    = array();
		foreach ($questions as $question) {
			$answers[$question->getId()]['name']           = $question->getName();
			$answers[$question->getId()]['theme']          = $question->getThemeQuestion()->getSlug();
			$answers[$question->getId()]['theme_nicename'] = $question->getThemeQuestion()->getName();
			$answers[$question->getId()]['responseType']   = $question->getResponseType()->getTypeField();
			$responseProposals                             = $question->getResponseProposal();
			foreach ($responseProposals as $responseProposal) {
				$answers[$question->getId()]['responseProposal'][$responseProposal->getId()] = $responseProposal->getResponse();
			}
			$responses = $em->getRepository('ChildConnectCCSoftBundle:Response')->findBy(array(
				'enfantQuiz' => $enfantQuiz,
				'question' => $question
			));
			foreach ($responses as $response) {
				if ($response->getCommentaire()) {
					$answers[$question->getId()]['commentaire'] = $response->getCommentaire();
				} elseif ($response->getResponseText()) {
					$answers[$question->getId()]['responses'] = $response->getResponseText();
				} elseif ($response->getResponseTextarea()) {
					$answers[$question->getId()]['responses'] = $response->getResponseTextarea();
				} elseif ($response->getResponseChoice()) {
					foreach ($question->getResponseProposal() as $rp)
						if ($rp->getId() == $response->getResponseChoice()->getId())
							$answers[$question->getId()]['responses'][$rp->getId()] = $rp->getResponse();
				}
			}
		}
		$a = array_values($answers);
		$this->assign('title_H1', $a[0]['theme_nicename']);
		return $this->renderTemplate('ChildConnectCCSoftBundle:Quiz:show-quiz-enfant.html.twig', array(
			'questionsResponses' => $answers,
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
	
	* Creates a form to delete a Quiz entity by id.
	
	*
	
	* @param mixed $id The entity id
	
	*
	
	* @return \Symfony\Component\Form\Form The form
	
	*/
	private function createDeleteForm($id)
	{
		return $this->createFormBuilder()->setAction($this->generateUrl('quiz_delete', array(
			'id' => $id
		)))->setMethod('DELETE')->add('submit', 'submit', array(
			'label' => 'Delete'
		))->getForm();
	}
	public function menuTopAction($actions = array(), $entities = array())
	{
		return parent::menuTop($actions, $entities);
	}
	public function printEnfantFicheAction(Enfant $enfant, Quiz $quiz)
	{
		$this->assign('id_body', 'printFiche');
		$em                    = $this->getDoctrine()->getManager();
		$enfantQuiz            = $em->getRepository('ChildConnectCCSoftBundle:EnfantQuiz')->findOneBy(array(
			'enfant' => $enfant,
			'quiz' => $quiz
		), array(
			'id' => 'DESC'
		));
		$questions             = $em->getRepository('ChildConnectCCSoftBundle:Question')->getQuestions($quiz, $enfant, $enfantQuiz);
		$responsesQuizDefault  = $em->getRepository('ChildConnectCCSoftBundle:ResponseProposal')->getResponsesProposalByQuiz($quiz);
		$responsesValueDefault = 0;
		$quiz_gauge_insertion  = array();
		foreach ($responsesQuizDefault as $responseQuizDefault)
			$responsesValueDefault += (int) $responseQuizDefault['value'];
		$quiz_gauge_insertion[$quiz->getId()]['responsesValueDefault'] = $responsesValueDefault;
		$responseEnfantValue                                           = 0;
		$answers                                                       = array();
		foreach ($questions as $question) {
			$answers[$question->getId()]['name']           = $question->getName();
			$answers[$question->getId()]['theme']          = $question->getThemeQuestion()->getSlug();
			$answers[$question->getId()]['theme_nicename'] = $question->getThemeQuestion()->getName();
			$answers[$question->getId()]['responseType']   = $question->getResponseType()->getTypeField();
			$responseProposals                             = $question->getResponseProposal();
			foreach ($responseProposals as $responseProposal) {
				$answers[$question->getId()]['responseProposal'][$responseProposal->getId()] = $responseProposal->getResponse();
			}
			$responses = $em->getRepository('ChildConnectCCSoftBundle:Response')->findBy(array(
				'enfantQuiz' => $enfantQuiz,
				'question' => $question
			));
			foreach ($responses as $response) {
				if ($question->getActiveIntegration() && $response->getResponseChoice())
					$responseEnfantValue += (int) $response->getResponseChoice()->getValue();
				if ($response->getCommentaire()) {
					$answers[$question->getId()]['commentaire'] = $response->getCommentaire();
				} elseif ($response->getResponseText()) {
					$answers[$question->getId()]['responses'] = $response->getResponseText();
				} elseif ($response->getResponseTextarea()) {
					$answers[$question->getId()]['responses'] = $response->getResponseTextarea();
				} elseif ($response->getResponseChoice()) {
					foreach ($question->getResponseProposal() as $rp)
						if ($rp->getId() == $response->getResponseChoice()->getId())
							$answers[$question->getId()]['responses'][$rp->getId()] = $rp->getResponse();
				}
			}
			$quiz_gauge_insertion[$quiz->getId()]['responseEnfantValue'] = $responseEnfantValue;
		}
		return $this->renderTemplate('ChildConnectCCSoftBundle:Quiz:print-fiche-enfant.html.twig', array(
			'questionsResponses' => $answers,
			'enfant' => $enfant,
			'quiz' => $quiz,
			'last_photo' => $em->getRepository('ChildConnectCCSoftBundle:Photo')->findOneBy(array(
				'enfant' => $enfant
			), array(
				'date' => 'DESC'
			)),
			'quiz_gauge_insertion' => $quiz_gauge_insertion
		));
	}
}

