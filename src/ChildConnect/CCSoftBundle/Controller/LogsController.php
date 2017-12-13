<?php
namespace ChildConnect\CCSoftBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use ChildConnect\CCSoftBundle\Entity\Logs;
/**
 * Logs controller.
 *
 */
class LogsController extends Controller
{
	/**
	 * Lists all Logs entities.
	 *
	 */
	public function indexAction()
	{
		$em       = $this->getDoctrine()->getManager();
		$entities = $em->getRepository('ChildConnectCCSoftBundle:Logs')->findAll();
		return $this->render('ChildConnectCCSoftBundle:Logs:index.html.twig', array(
			'entities' => $entities
		));
	}
	/**
	 * Finds and displays a Logs entity.
	 *
	 */
	public function showAction($id)
	{
		$em     = $this->getDoctrine()->getManager();
		$entity = $em->getRepository('ChildConnectCCSoftBundle:Logs')->find($id);
		if (!$entity) {
			throw $this->createNotFoundException('Unable to find Logs entity.');
		}
		return $this->render('ChildConnectCCSoftBundle:Logs:show.html.twig', array(
			'entity' => $entity
		));
	}
}
