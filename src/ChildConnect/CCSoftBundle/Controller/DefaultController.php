<?php
namespace ChildConnect\CCSoftBundle\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use ChildConnect\CCSoftBundle\Form\DefaultType;
class DefaultController extends FrontController
{
	public function __construct()
	{
		$this->assign('id_body', 'home');
		$this->assign('title_page', 'Child & Connect');
	}
	public function indexAction()
	{
		$securityContext = $this->get('security.context');
		if ($securityContext->isGranted('ROLE_ADMIN'))
			return $this->redirect($this->generateUrl('enfant_list', array(
				'orderBy' => 'modifiedAt',
				'sortBy' => 'DESC',
				'page' => 1,
				'nbrParPage' => 12
			)));
		else
			return $this->redirect($this->generateUrl('enfant', array(
				'orderBy' => 'modifiedAt',
				'sortBy' => 'DESC',
				'page' => 1,
				'nbrParPage' => 12
			)));
		$this->assign('title_H1', 'Tableau de bord');
		return $this->renderTemplate('ChildConnectCCSoftBundle:Default:index.html.twig');
	}
	public function getFooterAction()
	{
		return $this->renderTemplate('ChildConnectCCSoftBundle:Default:footer.html.twig');
	}
	public function getPageAction(Request $request, $page)
	{
		$args = array();
		switch ($page) {
			case 'mentionslegales':
				$template = 'mentions-legales';
				$this->assign('classes', array(
					'mentions-legales'
				));
				break;
			case 'contact':
				$template = 'contact';
				$form     = $this->createForm(new DefaultType(), NULL, array(
					'action' => $this->generateUrl('contact_page', array(
						'page' => $page
					)),
					'method' => 'POST'
				));
				$form->add('submit', 'submit', array(
					'label' => 'Envoyer'
				));
				if ($request->getMethod() == 'POST') {
					$form->handleRequest($request);
					if ($form->isValid()) {
						$stand      = $request['stand'];
						$printer    = $requestInArray['printer'];
						$cashDrawer = $requestInArray['cashDrawer'];
						$app        = $requestInArray['app'];
					}
				}
				$args['form_contact'] = $form->createView();
				break;
		}
		$this->assign('type_page', 'page');
		return $this->renderTemplate('ChildConnectCCSoftBundle:Default:' . $template . '.html.twig', $args);
	}
}