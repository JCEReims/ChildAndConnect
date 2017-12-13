<?php

namespace ChildConnect\CCSoftBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;


/**
 * Tools controller.
 *
 */
class ToolsController extends FrontController
{
    

	
	public function __construct() {
		
		$this->assign('id_body', 'tools');
		$this->assign('title_page','Tools Admin');
	
	}
	
	
    public function indexAction(Request $request)
    {
         if($request->getMethod() === 'POST') {
			 $button  = $request->request->get('action');
			 if($button == "videCache") {
				$this->videCache();
				  return $this->redirect($this->generateUrl('tools') );
			 }
			 if($button == "assoc_base_enfant") {
				$em = $this->getDoctrine()->getManager();
				$entities = $em->getRepository('ChildConnectCCSoftBundle:Enfant')->findAll();
				foreach($entities as &$entity) {
					//$enfant = $em->getRepository('ChildConnectCCSoftBundle:Enfant')->find($entity);
						foreach($entity->getAssociations() as $assoc) {
							//var_dump($assoc->getID());
							$entity->setAssociationBase($assoc->getID());
							$em->flush();
						}
				}
			 }
				
		 }
	
        return $this->renderTemplate('ChildConnectCCSoftBundle:Tools:index.html.twig', array(
        
        ));
    }
	private function  videCache() {
		$cache_dir = dirname(__FILE__) . '/../../../../app/cache';
				 echo "<b>cache_dir : $cache_dir</b>";
				 if (is_dir($cache_dir)) {
					if (basename($cache_dir) == "cache") {
						echo "<br/><br/><b>clearing cache :</b>";
						$this->cc($cache_dir, "dev");
						$this->cc($cache_dir, "prod");
						$this->cc($cache_dir, "test");
						echo "<br/><br/><b>done !</b>";
						 $this->get('session')->getFlashBag()->add('success', 'cache supprimé');
						//header('location: '.$_SERVER['SCRIPT_NAME'].'/tools');
						die();
				
				 
					}
					else {
						die("<br/> Error : cache_dir not named cache ?");
					}
				}
				else {
					die("<br/> Error : cache_dir is not a dir");
				}
	}
	
	public function rrmdir($dir)   {
		if (is_dir($dir))
			{
			$objects = scandir($dir);
			foreach($objects as $object)
				{
				if ($object != "." && $object != "..")
					{
					$o = $dir . "/" . $object;
					if (filetype($o) == "dir")
						{
						$this->rrmdir($dir . "/" . $object);
						}
					  else
						{
						echo "<br/>" . $o;
						@unlink($o);
						}
					}
				}
	
			reset($objects);
			rmdir($dir);
			}
	}

	public function cc($cache_dir, $name) {
		$d = $cache_dir . '/' . $name;
		if (is_dir($d))
			{
			echo "<br/><br/><b>clearing " . $name . ' :</b>';
			$this->rrmdir($d);
			}
	}
}
