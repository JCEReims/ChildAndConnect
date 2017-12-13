<?php
namespace ChildConnect\CCSoftBundle\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use ChildConnect\CCSoftBundle\Form\SearchCodeBoxType;
use ChildConnect\CCSoftBundle\Form\SearchEnfantType;
use ChildConnect\CCSoftBundle\Entity\Quiz;
use ChildConnect\CCSoftBundle\Entity\Question;
class WidgetController extends FrontController
{
	public function __construct()
	{
	}
	public function searchCodeBoxAction($instance = NULL)
	{
		$form_searchcodebox = $this->createForm(new SearchCodeBoxType(), NULL, array(
			'action' => $this->generateUrl('enfant'),
			'method' => 'POST'
		));
		$form_searchcodebox->add('submit', 'submit', array(
			'label' => 'Rechercher'
		));
		return $this->renderTemplate('ChildConnectCCSoftBundle:Default:search-code-box.html.twig', array(
			'instance' => $instance,
			'form_searchcodebox' => $form_searchcodebox->createView()
		));
	}
	public function searchEnfantAction($instance = NULL)
	{
		$form_search = $this->createForm(new SearchEnfantType(), NULL, array(
			'action' => $this->generateUrl('enfant_search'),
			'method' => 'POST'
		));
		/*$form_search->add('submit', 'submit', array(
		
		'label' => '\f400',
		
		'attr' => array(
		
		'class' => 'genericon  genericon-search btn-search'
		
		)
		
		));*/
		return $this->renderTemplate('ChildConnectCCSoftBundle:Default:search-enfant.html.twig', array(
			'instance' => $instance,
			'form_search' => $form_search->createView()
		));
	}
	public function sidebarAction($actions = array(), $entities = array())
	{
		return parent::sidebar($actions, $entities);
	}
	public function menuTopAction($entities = NULL, $options = NULL)
	{
		return $this->renderTemplate('ChildConnectCCSoftBundle::menuTop.html.twig', array(
			'btns' => parent::menuTop($entities, $options),
			'actif' => (isset($options['actif'])) ? $options['actif'] : NULL
		));
	}
	public function menuEnfantAction($entities = NULL, $options = NULL)
	{
		return $this->renderTemplate('ChildConnectCCSoftBundle::menuEnfant.html.twig', array(
			'btns' => parent::menuEnfant($entities, $options),
			'actif' => (isset($options['actif'])) ? $options['actif'] : NULL
		));
	}
	public function breadcrumbCreateEntantAction($params = array())
	{
		$em     = $this->getDoctrine()->getManager();
		$quiz   = $em->getRepository('ChildConnectCCSoftBundle:Quiz')->find(1);
		$themes = $em->getRepository('ChildConnectCCSoftBundle:ThemeQuestion')->getThemes($quiz);
		//var_dump($themes);
		return $this->renderTemplate('ChildConnectCCSoftBundle:Default:breadcrumb-create-enfant.html.twig', array(
			'themes' => $themes,
			'params' => $params,
			'quiz' => $quiz
		));
	}
}

