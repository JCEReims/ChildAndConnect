<?php 
namespace ChildConnect\CCSoftBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\ORM\EntityManager;

class FrontController extends Controller {
	
	private $idBody = 'home';
	private $titlePage = 'Child & Connect C&C soft';
	private $class_form;
	private $session;
	private $tpl_vars = array();
	private $em = NULL;
	
	public function __construct(EntityManager $entityManager=null)
	{
		if($entityManager)
			$this->em = $entityManager;
		else
			$this->em = $this->getDoctrine()->getManager();
	}
	
	 /**
     * assigns a twig variable
     * 
     * @param array $ |string $tpl_var the template variable name(s)
     * @param mixed $value the value to assign
     */
    public function assign($tpl_var, $value = null)
    {
        if (is_array($tpl_var)) {
            foreach ($tpl_var as $_key => $_val) {
                if ($_key != '') {
                    $this->tpl_vars[$_key] = $_val;
                } 
            } 
        } else {
            if ($tpl_var != '') {
                $this->tpl_vars[$tpl_var] = $value;
				
            } 
        } 
		
    } 
	public function  renderTemplate($template,$tpl_vars = null) {
		
		
		$this->globalAssign($tpl_vars);
		$engine = $this->container->get('templating');
		$content = $engine->render($template,$this->tpl_vars);

		return $response = new Response($content);

	
	}
	
	 /**
     * Returns a rendered view.
     *
     * @param string $view       The view name
     * @param array  $parameters An array of parameters to pass to the view
     *
     * @return string The rendered view
     */
    public  function renderView($view, array $parameters = array(),$tpl_vars = null)
    {
         $this->globalAssign();
		
		$parameters = array_merge($this->tpl_vars,$parameters);
			
		return $this->container->get('templating')->render($view, $parameters);
    }

	public  function globalAssign($tpl_vars = null) {
		
		if(!$this->em)
			$this->em = $this->getDoctrine()->getManager();
			
		$this->assign('img_url' ,'bundles/childconnectccsoft/images/');
		 $this->assign('js_url', 'bundles/childconnectccsoft/js/');
		$this->assign('css_url', 'bundles/childconnectccsoft/css/');
		$ua = $this->getBrowser();

		
		if( is_array($tpl_vars) )
			$this->tpl_vars = array_merge($this->tpl_vars,$tpl_vars);
	
		
		return $this->tpl_vars;
					
	}
	private function getIdBody() {
		
		return $this->tpl_vars['id_body'];
	}
	public function getTitlePage() {
		return $this->titlePage;
	}
	
	public function controllerAction() {
		$this->assign('class_form',$this->getIdBody().' '.$this->_getActionName());

	}
	
	private function _getActionName() {
		
		$matches    = array();
		$controller = $this->getRequest()->attributes->get('_controller');

		preg_match('/(.*)\\\(.*)Bundle\\\Controller\\\(.*)Controller::(.*)Action/', $controller, $matches);
		 
		/*$request = $this->getRequest();
		$request->attributes->set('namespace',  $matches[1]);
		$request->attributes->set('bundle',     $matches[2]);
		$request->attributes->set('controller', $matches[3]);
		$request->attributes->set('action',     $matches[4]);*/

		return $matches[4];
	}
	
	
	
 private	function getBrowser() 
	{ 

		$u_agent = $_SERVER['HTTP_USER_AGENT']; 
		$bname = 'Unknown';
		$platform = 'Unknown';
		$version= "";
		
		//First get the platform?
		if (preg_match('/linux/i', $u_agent)) {
			$platform = 'linux';
		}
		elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
			$platform = 'mac';
		}
		elseif (preg_match('/windows|win32/i', $u_agent)) {
			$platform = 'windows';
		}
		//var_dump($u_agent);
		// Next get the name of the useragent yes seperately and for good reason
		if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)) 
		{ 
			$bname = 'Internet Explorer'; 
			$ub = "MSIE"; 
		} 
		elseif(preg_match('/Firefox/i',$u_agent)) 
		{ 
			$bname = 'Mozilla Firefox'; 
			$ub = "Firefox"; 
		} 
		elseif(preg_match('/Chrome/i',$u_agent)) 
		{ 
			$bname = 'Google Chrome'; 
			$ub = "Chrome"; 
		} 
		elseif(preg_match('/Safari/i',$u_agent)) 
		{ 
			$bname = 'Apple Safari'; 
			$ub = "Safari"; 
		} 
		elseif(preg_match('/Opera/i',$u_agent)) 
		{ 
			$bname = 'Opera'; 
			$ub = "Opera"; 
		} 
		elseif(preg_match('/Netscape/i',$u_agent)) 
		{ 
			$bname = 'Netscape'; 
			$ub = "Netscape"; 
		} 
		elseif(preg_match('/Trident/i',$u_agent)) 
		{ 
			$bname = 'Internet Explorer';
			$ub = "Trident"; 
		} 
		
	
		// finally get the correct version number
		$known = array('Version', $ub, 'other');
		$pattern = '#(?<browser>' . join('|', $known) .
		')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
		if (!preg_match_all($pattern, $u_agent, $matches)) {
			// we have no matching number just continue
		}
	
		// see how many we have
		$i = count($matches['browser']);
		if ($i != 1) {
			//we will have two since we are not using 'other' argument yet
			//see if version is before or after the name
			if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
				$version= $matches['version'][0];
			}
			else {
				$version= $matches['version'][1];
			}
		}
		else {
			$version= $matches['version'][0];
		}
	
		// check if we have a number
		if ($version==null || $version=="") {$version="?";}
		
		return array(
			'userAgent' => $u_agent,
			'name'      => $bname,
			'version'   => $version,
			'platform'  => $platform,
			'pattern'    => $pattern
		);
	} 
	
	public function menu($actions=array(),$entities=NULL,$options=NULL) {
		$btns = array();
		$securityContext = $this->get('security.context');
		$em = $this->getDoctrine()->getManager();
		
		
		foreach($actions as $action ) {
			switch($action) {
				
				case 'home' :
					$btns['home']['href'] =  $this->generateUrl('enfant',array('orderBy' => 'modifiedAt', 'sortBy' => 'DESC', 'page' => 1, 'nbrParPage' => 12));
					$btns['home']['title'] =  '';
					$btns['home']['class'][] =  'btn_home';
					$btns['home']['class'][] =  'genericon ';
					if(isset($options['actif']) && $options['actif'] == "home")
							$btns['home']['class'][] =  'actif';
				break;
				case 'enfants' :
					$btns['list_enfant']['href'] =  $this->generateUrl('enfant_list',array('orderBy' => 'modifiedAt', 'sortBy' => 'DESC', 'page' => 1, 'nbrParPage' => 12));
					$btns['list_enfant']['title'] =  'Liste des Enfants';
					$btns['list_enfant']['class'][] =  'list_enfant';
					if(isset($options['actif']) && $options['actif'] == "enfant")
							$btns['list_enfant']['class'][] =  'actif';
				break;
				case 'add_enfant' :
					if($securityContext->isGranted('ROLE_ADMIN_ASSOC'))  {
						$btns['add_enfant']['href'] =  $this->generateUrl('enfant_new');
						$btns['add_enfant']['title'] =  'Ajouter un enfant';
						$btns['add_enfant']['class'][] =  'add_enfant';
					}
				break;
				case 'dashboard' :
					$btns['dashboard']['href'] =  $this->generateUrl('home');
					$btns['dashboard']['title'] =  'Tableau de bord';
					$btns['dashboard']['class'][] =  'dashboard';
				break;
				case 'edit_enfant' :
					if($securityContext->isGranted('ROLE_ADMIN_ASSOC'))  {
						$btns['edit_enfant']['href'] =  $this->generateUrl('enfant_edit',array('id' => $entities['enfant']->getId()));
						$btns['edit_enfant']['title'] =  'Modifier cette fiche';
						$btns['edit_enfant']['class'][] =  'edit_enfant';
					}
				break;
				
				case 'localisation' :
					$btns['localisation']['href'] =  $this->generateUrl('lieu_new',array('enfant' => $entities['enfant']->getId(), 'quiz' => $entities['quiz']->getId()));
					$btns['localisation']['title'] =  'Localisation';
					$btns['localisation']['class'][] =  'add_lieu';
					if(isset($options['actif']) && $options['actif'] == "lieu")
							$btns['localisation']['class'][] =  'actif';
				break ;
				case 'identite' :
					$btns['identite']['href'] =  $this->generateUrl('enfant_show',array('id' => $entities['enfant']->getId()));
					$btns['identite']['title'] =  'Identité';
					$btns['identite']['class'][] =  'identite';
					if(isset($options['actif']) && $options['actif'] == "enfant_show")
							$btns['identite']['class'][] =  'actif';
				break ;
				
				case 'quiz_show_enfant' :
					
					$themes= $em->getRepository('ChildConnectCCSoftBundle:ThemeQuestion')->getThemes($entities['quiz']);
					
					$btns['quiz_show_enfant']['href'] =  $this->generateUrl('quiz_show_enfant',array('quiz' => $entities['quiz']->getId(), 'enfant' =>$entities['enfant']->getId() ));
					$btns['quiz_show_enfant']['title'] =  'Evolutions';
					$btns['quiz_show_enfant']['class'][] =  'quiz_show_enfant';
					foreach($themes as $theme) {
						$btns['quiz_show_enfant']['sousMenu'][$theme->getId()]['href'] = $this->generateUrl('quiz_show_enfant',array('quiz' => $entities['quiz']->getId(), 'enfant' =>$entities['enfant']->getId() )).'#'.$theme->getSlug();
						$btns['quiz_show_enfant']['sousMenu'][$theme->getId()]['title'] = $theme->getName();
						$btns['quiz_show_enfant']['sousMenu'][$theme->getId()]['slug'] = $theme->getSlug();
						$btns['quiz_show_enfant']['sousMenu'][$theme->getId()]['class'][] =  'btn_menu_top_evolution';
					}
					if(isset($options['actif']) && $options['actif'] == "evolution")
							$btns['quiz_show_enfant']['class'][] =  'actif';
				
				break;
				case 'edit_quiz_enfant' :
					if($securityContext->isGranted('ROLE_ADMIN_ASSOC'))  {
						$btns['edit_quiz_enfant']['href'] =  $this->generateUrl('quiz_complete',array('quiz' => $entities['quiz']->getId(), 'enfant' =>$entities['enfant']->getId() ));
						$btns['edit_quiz_enfant']['title'] =  'Modifier les réponses';
						$btns['edit_quiz_enfant']['class'][] =  'edit_quiz_enfant';
					}
				break;
				case 'edit_event' :
					$btns['edit_event']['href'] =  $this->generateUrl('event_edit',array('id' => $entities['event']->getId() ));
					$btns['edit_event']['title'] =  'Modifier l\'événement';
					$btns['edit_event']['class'][] =  'edit_event';
					
				break;
				case 'list_event' :
					$btns['list_event']['href'] =  $this->generateUrl('event',array('enfant' => $entities['enfant']->getId(),'quiz' => $entities['quiz']->getId()));
					$btns['list_event']['title'] =  'Les évènements';
					$btns['list_event']['class'][] =  'list_event';
					if(isset($options['actif']) && $options['actif'] == "event")
							$btns['list_event']['class'][] =  'actif';
				break;
				case 'add_event' :
					$btns['add_event']['href'] =  $this->generateUrl('event_new',array('enfant' => $entities['enfant']->getId()));
					$btns['add_event']['title'] =  'Ajouter un évènement';
					$btns['add_event']['class'][] =  'add_event';
				break;
				case 'back_event' :
					$btns['back_event']['href'] =  $this->generateUrl('event',array('enfant' => $entities['enfant']->getId()));
					$btns['back_event']['title'] =  'Retour événement';
					$btns['back_event']['class'][] =  'back_event';
				break ;
				case 'all_events' :
					$btns['all_events']['href'] =  $this->generateUrl('event_all');
					$btns['all_events']['title'] =  'Tous Les évènements';
					$btns['all_events']['class'][] =  'all_events';
					if(isset($options['actif']) && $options['actif'] == "all_events")
							$btns['all_events']['class'][] =  'actif';
				break;
				case 'add_assoc' :
					$btns['add_assoc']['href'] =  $this->generateUrl('association_new');
					$btns['add_assoc']['title'] =  'Ajouter une association';
					$btns['add_assoc']['class'][] =  'add_assoc';
				break ;
				case 'associations' :
					$btns['list_assoc']['href'] =  $this->generateUrl('association');
					$btns['list_assoc']['title'] =  'Les Associations';
					$btns['list_assoc']['class'][] =  'list_assoc';
					if(isset($options['actif']) && $options['actif'] == "association")
							$btns['list_assoc']['class'][] =  'actif';
				break ;
				case 'quizs' :
				if($securityContext->isGranted('ROLE_ADMIN'))  {
					$btns['list_quiz']['href'] =  $this->generateUrl('quiz');
					$btns['list_quiz']['title'] =  'Les questionnaires';
					$btns['list_quiz']['class'][] =  'list_assoc';
					if(isset($options['actif']) && $options['actif'] == "quizs")
							$btns['list_quiz']['class'][] =  'actif';
				}
				break ;
				case 'add_quiz' :
					if($securityContext->isGranted('ROLE_SUPER_ADMIN')) {
						$btns['add_quiz']['href'] =  $this->generateUrl('quiz_new');
						$btns['add_quiz']['title'] =  'Ajouter un questionnaire';
						$btns['add_quiz']['class'][] =  'add_quiz';
					}
				break;
				case 'tools' :
					if($securityContext->isGranted('ROLE_SUPER_ADMIN')) {
						$btns['tools']['href'] =  $this->generateUrl('tools');
						$btns['tools']['title'] =  'Tools';
						$btns['tools']['class'][] =  'tools';
					}
				break;
				case 'add_user' :
				if($securityContext->isGranted('ROLE_ADMIN_ASSOC'))  {
						$btns['add_user']['href'] =  $this->generateUrl('user_new');
						$btns['add_user']['title'] =  'Ajouter un utilisateur';
						$btns['add_user']['class'][] =  'add_user';
				}
				break;
				case 'users' :
				if($securityContext->isGranted('ROLE_ADMIN_ASSOC'))  {
						$btns['list_users']['href'] =  $this->generateUrl('user');
						$btns['list_users']['title'] =  'Les utilisateurs';
						$btns['list_users']['class'][] =  'list_users';
						if(isset($options['actif']) && $options['actif'] == "user")
							$btns['list_users']['class'][] =  'actif';
				}
				break;
				case 'stats' :
						$btns['stats']['href'] =  "#";
						$btns['stats']['title'] =  'Statistiques';
						$btns['stats']['class'][] =  'stats';
				break;
			}
		}
		return $btns;
		
	}
	
	
	public function menuTop($options=NULL) {
		$btns = array();
		$securityContext = $this->get('security.context');
		$em = $this->getDoctrine()->getManager();
		$baseurl = $this->get('request')->getScheme() . '://' . $this->get('request')->getHttpHost() . $this->get('request')->getBasePath(); 
		
		/* enfants' */
		$btns['list_enfant']['href'] =  $this->generateUrl('enfant_list',array('orderBy' => 'modifiedAt', 'sortBy' => 'DESC', 'page' => 1, 'nbrParPage' => 12));
		$btns['list_enfant']['title'] =  'Liste des Enfants';
		$btns['list_enfant']['class'][] =  'list_enfant';
		if(isset($options['actif']) && $options['actif'] == "enfant")
			$btns['list_enfant']['class'][] =  'actif';
		
		/*add_enfant */
		if($securityContext->isGranted('ROLE_ADMIN_ASSOC'))  {
			$btns['add_enfant']['href'] =  $this->generateUrl('enfant_new');
			$btns['add_enfant']['title'] =  'Ajouter un enfant';
			$btns['add_enfant']['class'][] =  'add_enfant';
		}
		
		/*all_events*/
		$btns['all_events']['href'] =  $this->generateUrl('event_all');
		$btns['all_events']['title'] =  'Tous Les évènements';
		$btns['all_events']['class'][] =  'all_events';
		if(isset($options['actif']) && $options['actif'] == "all_events")
			$btns['all_events']['class'][] =  'actif';
			
		
		/* BTN AUTRES */
		$btns['autres']['href'] =  '#';
		$btns['autres']['title'] =  'Autres';
		$btns['autres']['class_li'][] =  'right';
		$btns['autres']['class_li'][] =  'no_border';
		$btns['autres']['class'][] =  'btn_autres';
		
		/*stats*/
		if($securityContext->isGranted('ROLE_ADMIN_ASSOC'))  {
			$btns['stats']['href'] =  $this->generateUrl('statistique');
			$btns['stats']['title'] =  'Statistiques';
			$btns['stats']['class'][] =  'stats';
			$btns['stats']['class_li'][] =  'right';
			
		}
		
		
		$btns['autres']['sousMenuClass']['class'][] = 'stick_right';
		/*associations*/
		$btns['autres']['sousMenu']['list_assoc']['href'] =  $this->generateUrl('association');
		$btns['autres']['sousMenu']['list_assoc']['title'] =  'Les Associations';
		$btns['autres']['sousMenu']['list_assoc']['class'][] =  'list_assoc';
		if(isset($options['actif']) && $options['actif'] == "association")
			$btns['autres']['sousMenu']['list_assoc']['class'][] =  'actif';
			/*users*/
		if($securityContext->isGranted('ROLE_ADMIN_ASSOC'))  {
			$btns['autres']['sousMenu']['list_users']['href'] =  $this->generateUrl('user');
			$btns['autres']['sousMenu']['list_users']['title'] =  'Les utilisateurs';
			$btns['autres']['sousMenu']['list_users']['class'][] =  'list_users';
			if(isset($options['actif']) && $options['actif'] == "user")
				$btns['autres']['sousMenu']['list_users']['class'][] =  'actif';
		}
		if($securityContext->isGranted('ROLE_ADMIN'))  {
			$btns['autres']['sousMenu']['export_excel']['href'] =  $this->generateUrl('export_excel');
			$btns['autres']['sousMenu']['export_excel']['title'] =  'Export excel';
			$btns['autres']['sousMenu']['export_excel']['class'][] =  'export_excel';
			if(isset($options['actif']) && $options['actif'] == "export_excel")
				$btns['autres']['sousMenu']['export_excel']['class'][] =  'actif';
		}
		
			$btns['autres']['sousMenu']['pdf']['href'] = $baseurl . '/uploads/pdfs/Questionnaire_association.pdf';
			$btns['autres']['sousMenu']['pdf']['title'] =  'Questionnnaire PDF';
			$btns['autres']['sousMenu']['pdf']['options'] = 'target="new"';
			$btns['autres']['sousMenu']['pdf']['class'][] =  'list_users';
			if(isset($options['actif']) && $options['actif'] == "user")
				$btns['autres']['sousMenu']['list_users']['class'][] =  'actif';
		
		
		/*quizs*/
		if($securityContext->isGranted('ROLE_ADMIN'))  {
		$btns['autres']['sousMenu']['list_quiz']['href'] =  $this->generateUrl('quiz');
		$btns['autres']['sousMenu']['list_quiz']['title'] =  'Les questionnaires';
		$btns['autres']['sousMenu']['list_quiz']['class'][] =  'list_assoc';
		if(isset($options['actif']) && $options['actif'] == "quizs")
			$btns['autres']['sousMenu']['list_quiz']['class'][] =  'actif';
		}
		/*add_quiz*/
		if($securityContext->isGranted('ROLE_SUPER_ADMIN')) {
			$btns['autres']['sousMenu']['add_quiz']['href'] =  $this->generateUrl('quiz_new');
			$btns['autres']['sousMenu']['add_quiz']['title'] =  'Ajouter un questionnaire';
			$btns['autres']['sousMenu']['add_quiz']['class'][] =  'add_quiz';
		}
		/*tools*/
		if($securityContext->isGranted('ROLE_SUPER_ADMIN')) {
			$btns['autres']['sousMenu']['tools']['href'] =  $this->generateUrl('tools');
			$btns['autres']['sousMenu']['tools']['title'] =  'Tools';
			$btns['autres']['sousMenu']['tools']['class'][] =  'tools';
			if(isset($options['actif']) && $options['actif'] == "tools")
				$btns['autres']['sousMenu']['tools']['class'][] =  'actif';
		}
		
		return $btns;
		
	}
	
	public function menuEnfant($entities=NULL,$options=NULL) {
		$btns = array();
		$securityContext = $this->get('security.context');
		$em = $this->getDoctrine()->getManager();
			
			/*identite*/
					$btns['identite']['href'] =  $this->generateUrl('enfant_show',array('id' => $entities['enfant']->getId()));
					$btns['identite']['title'] =  'Identité';
					$btns['identite']['class'][] =  'identite';
					if(isset($options['actif']) && $options['actif'] == "enfant_show")
						$btns['identite']['class'][] =  'actif';
			
				/*quiz_show_enfant*/
					
					$themes = $em->getRepository('ChildConnectCCSoftBundle:ThemeQuestion')->getThemes($entities['quiz']);
					foreach($themes as $theme) {
						$btns[$theme->getSlug()]['href'] = $this->generateUrl('quiz_show_enfant',array('quiz' => $entities['quiz']->getId(), 'enfant' =>$entities['enfant']->getId() )).'#'.$theme->getSlug();
						$btns[$theme->getSlug()]['title'] = $theme->getName();
						$btns[$theme->getSlug()]['slug'] = $theme->getSlug();
						$btns[$theme->getSlug()]['class'][] =  'btn_menu_'.$theme->getSlug();
						if(isset($options['actif']) && $options['actif'] == $theme->getSlug())
							$btns[$theme->getSlug()]['class'][] =  'actif';
					}
					
				
				/*list_event*/
					$btns['list_event']['href'] =  $this->generateUrl('event',array('enfant' => $entities['enfant']->getId(),'quiz' => $entities['quiz']->getId()));
					$btns['list_event']['title'] =  'Les évènements';
					$btns['list_event']['class'][] =  'list_event';
					if(isset($options['actif']) && $options['actif'] == "event")
							$btns['list_event']['class'][] =  'actif';
			/*localisation*/
					$btns['localisation']['href'] =  $this->generateUrl('lieu_new',array('enfant' => $entities['enfant']->getId(), 'quiz' => $entities['quiz']->getId()));
					$btns['localisation']['title'] =  'Localisation';
					$btns['localisation']['class'][] =  'add_lieu';
					if(isset($options['actif']) && $options['actif'] == "lieu")
							$btns['localisation']['class'][] =  'actif';;
			
				
		
		return $btns;
		
	}
	
}
?>