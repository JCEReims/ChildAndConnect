<?php
namespace ChildConnect\CCSoftBundle\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use ChildConnect\CCSoftBundle\Form\StatistiqueType;
use ChildConnect\CCSoftBundle\Form\ExportExcelType;
use JMS\Serializer\SerializationContext;
class StatistiqueController extends FrontController
{
	public function __construct()
	{
		$this->assign('id_body', 'statistique');
		$this->assign('title_page', 'Statistiques');
		$this->assign('classes', array(
			'inner_content_no_border'
		));
	}
	public function indexAction(Request $request = null)
	{
		$em = $this->getDoctrine()->getManager();
		$form_filtres = $this->choixStats($request);
		$serieName = array(
			'reponses_question' => 'Réponses à la question',
			'sexe' => 'Sexe',
			'age' => 'Par tranche d\'age'
		);
		if ($request->getMethod() == 'POST') {
			
			$categories = array();
			$results = array();
			$serieOne = array();
			$serieTwo = array();
			$form = $request->request->get('childconnect_ccsoftbundle_statistiquetype');
			if (isset($form['submit'])) {
				
				if ($form['association'] != 'all')
					$association = $em->getRepository('ChildConnectCCSoftBundle:Association')->find((int) $form['association']);
				else
					$association = 0;
				
				$question = $em->getRepository('ChildConnectCCSoftBundle:Question')->find((int) $form['question']);
				$this->assign('laQuestion', $question);
				$pas_tranche_age = (isset($form['select_pas_age'])) ? $form['select_pas_age'] : 2;
				if($pas_tranche_age)
					for ($i = $pas_tranche_age; $i <= 18; $i += $pas_tranche_age)
						$tranche_age[$i] = 'de ' . ($i - $pas_tranche_age) . ' à ' . $i . ' ans';
				$date_start = date('Y-m-d', strtotime($form['dateStart'])).' 00:00:00';
				$date_end = date('Y-m-d', strtotime($form['dateEnd'])).' 23:59:59';
                                
				$e_actif = $form['enfant_actif_inactif'] == '1' ? 1 : 0;
                                
				$responses_totales = $em->getRepository('ChildConnectCCSoftBundle:Response')->getResponseByQuestionId($question, $e_actif, $association, $date_start, $date_end);
				$responsesProposal = $em->getRepository('ChildConnectCCSoftBundle:ResponseProposal')->getByQuestion($question);
				
				if ($responses_totales) {
					/* NIVEAU 1 */
					if (isset($form['select_one']) && $form['select_one'] != '0') {
						if ($form['select_one'] == 'reponses_question') {
							$serieOne['name'] = "Réponses à la question";
							$serieOne['distance'] = -55;
							$serieOne['size'] = '60%';
							foreach ($responsesProposal as $rp) {
								$responses = $em->getRepository('ChildConnectCCSoftBundle:Response')->getResponsesByResponseProposal($question, $rp, $association);
								$serieOne['responses'][$rp->getId()] = array(
									'name' => $rp->getResponse(),
									'value' => count($responses) ,//round((100 * count($responses)) / count($responses_totales), 2),
									'id_one' => $rp->getId()
								);
							} //$responsesProposal as $rp
							$this->assign('serieOne', $serieOne);
						} //$form['select_one'] == 'reponses_question'
						elseif ($form['select_one'] == 'sexe') {
							$serieOne['name'] = "Sexe";
							$serieOne['distance'] = -55;
							$serieOne['size'] = '60%';
							$sexes['M'] = 0;
							$sexes['F'] = 0;
							foreach ($responses_totales as $response) {
								if ($response->getEnfantQuiz()->getEnfant()->getSexe() == 'M')
									$sexes['M'] = ($sexes['M']) ? $sexes['M'] + 1 : 1;
								else
									$sexes['F'] = ($sexes['F']) ? $sexes['F'] + 1 : 1;
							} //$responses_totales as $response
							
							foreach ($sexes as $sexe => $val)
								$serieOne['responses'][] = array(
									'name' => ($sexe == 'M') ? 'Garçon' : 'Fille',
									'value' => $val , //round((100 * $val) / count($responses_totales), 2),
									'id_one' => $sexe
								);
						} //$form['select_one'] == 'sexe'
							elseif ($form['select_one'] == 'age') {
							$serieOne['name'] = "Age";
							$serieOne['distance'] = -55;
							$serieOne['size'] = '60%';
							$ages = array();
							foreach ($responses_totales as $response) {
								$age_enfant = date('Y') - $response->getEnfantQuiz()->getEnfant()->getDateNaissance()->format('Y');
								//var_dump('enfant id '.$response->getEnfantQuiz()->getEnfant()->getId());
								$tranche_age_selected = 0;
								foreach ($tranche_age as $age => $text) {
									if ($age_enfant + 1 >= $age)
										$tranche_age_selected = $age;
								} //$tranche_age as $age => $text
								$ages[$tranche_age_selected] = (isset($ages[$tranche_age_selected])) ? $ages[$tranche_age_selected] + 1 : 1;
							} //$responses_totales as $response
							foreach ($ages as $age => $val)
								$serieOne['responses'][$age] = array(
									'name' => $tranche_age[$age],
									'value' => $val ,//round((100 * $val) / count($responses_totales), 2),
									'id_one' => $age
								);
						} //$form['select_one'] == 'age'
						$results['serieOne'] = $serieOne;
					} //isset($form['select_one']) && $form['select_one'] != '0'
					/* NIVEAU 2 */
					if (isset($form['select_two']) && $form['select_two'] != '0') {
						if ($form['select_two'] == 'reponses_question') {
							$serieTwo['name'] = 'Réponses à la question';
							$serieTwo['size'] = '80%';
							$serieTwo['innerSize'] = '60%';
							$responses = array();
							foreach ($serieOne['responses'] as $rs) {
								$id_one = $rs['id_one'];
								foreach ($responses_totales as $id_r => $response) {
									switch ($form['select_one']) {
										case 'sexe':
											$compare_one = $response->getEnfantQuiz()->getEnfant()->getSexe();
											break;
										case 'age':
											$age_enfant = date('Y') - $response->getEnfantQuiz()->getEnfant()->getDateNaissance()->format('Y');
											foreach ($tranche_age as $age => $text) {
												if ($age_enfant + 1 >= $age)
													$tranche_age_selected = $age;
											} //$tranche_age as $age => $text
											$compare_one = $tranche_age_selected;
											break;
									} //$form['select_one']
									if ($compare_one == $id_one)
										$responses[$id_one][$response->getResponseChoice()->getId()] = (isset($responses[$id_one][$response->getResponseChoice()->getId()])) ? $responses[$id_one][$response->getResponseChoice()->getId()] + 1 : 1;
								} //$responses_totales as $id_r => $response
							} //$serieOne['responses'] as $rs
							foreach ($responses as $id_one => $response) {
								foreach ($response as $id => $val)
									$serieTwo['responses'][] = array(
										'name' => $responsesProposal[$id]->getResponse(),
										'value' => $val ,//round((100 * $val) / count($responses_totales), 2),
										'id_one' => $id_one,
										'id_two' => $id
									);
							} //$responses as $id_one => $response
						} //$form['select_two'] == 'reponses_question'
						elseif ($form['select_two'] == 'sexe') {
							$serieTwo['name'] = 'Sexe';
							$serieTwo['size'] = '80%';
							$serieTwo['innerSize'] = '60%';
							foreach ($serieOne['responses'] as $id_one => $rs) {
								$sexes[$id_one]['M'] = 0;
								$sexes[$id_one]['F'] = 0;
							} //$serieOne['responses'] as $id_one => $rs
							foreach ($serieOne['responses'] as $id_one => $rs) {
								foreach ($responses_totales as $response) {
									switch ($form['select_one']) {
										case 'reponses_question':
											$compare_one = $response->getResponseChoice()->getId();
											break;
										case 'age':
											$age_enfant = date('Y') - $response->getEnfantQuiz()->getEnfant()->getDateNaissance()->format('Y');
											foreach ($tranche_age as $age => $text) {
												if ($age_enfant + 1 >= $age)
													$tranche_age_selected = $age;
											} //$tranche_age as $age => $text
											$compare_one = $tranche_age_selected;
											break;
									} //$form['select_one']
									if ($compare_one == $rs['id_one']) {
										if ($response->getEnfantQuiz()->getEnfant()->getSexe() == 'M')
											$sexes[$id_one]['M'] = ($sexes[$id_one]['M']) ? $sexes[$id_one]['M'] + 1 : 1;
										else
											$sexes[$id_one]['F'] = ($sexes[$id_one]['F']) ? $sexes[$id_one]['F'] + 1 : 1;
									} //$compare_one == $rs['id_one']
								} //$responses_totales as $response
							} //$serieOne['responses'] as $id_one => $rs
							foreach ($sexes as $id_one => $sex)
								foreach ($sex as $sexe => $val) {
									$serieTwo['responses'][] = array(
										'name' => ($sexe == 'M') ? 'Garçon' : 'Fille',
										'value' => $val,//round((100 * $val) / count($responses_totales), 2),
										'id_one' => $id_one,
										'id_two' => $sexe
									);
								} //$sex as $sexe => $val
						} //$form['select_two'] == 'sexe'
							elseif ($form['select_two'] == 'age') {
							$serieTwo['name'] = 'Age';
							$serieTwo['size'] = '80%';
							$serieTwo['innerSize'] = '60%';
							foreach ($serieOne['responses'] as $id_one => $rs) {
								foreach ($responses_totales as $response) {
									switch ($form['select_one']) {
										case 'reponses_question':
											$compare_one = $response->getResponseChoice()->getId();
											break;
										case 'sexe':
											$compare_one = $response->getEnfantQuiz()->getEnfant()->getSexe();
											break;
									} //$form['select_one']
									if ($compare_one == $rs['id_one']) {
										$age_enfant = date('Y') - $response->getEnfantQuiz()->getEnfant()->getDateNaissance()->format('Y');
										foreach ($tranche_age as $age => $text) {
											if ($age_enfant + 1 >= $age)
												$tranche_age_selected = $age;
										} //$tranche_age as $age => $text
										//$serieThree['responses'][] = array('name' => $tranche_age['tranche_age_selected'], 'value' => $val,'id_rp' => $id_rp,'slug' => ($sexe == 'male') ? 'M' : 'F');
										//var_dump($tranche_age_selected);
										$ages[$rs['id_one']][$tranche_age_selected] = (isset($ages[$rs['id_one']][$tranche_age_selected])) ? $ages[$rs['id_one']][$tranche_age_selected] + 1 : 1;
									} //$compare_one == $rs['id_one']
								} //$responses_totales as $response
							} //$serieOne['responses'] as $id_one => $rs
							foreach ($ages as $id_one => $niv2)
								foreach ($niv2 as $tranche_age_key => $val) {
									$serieTwo['responses'][] = array(
										'name' => $tranche_age[$tranche_age_key],
										'value' => $val,//round((100 * $val) / count($responses_totales), 2),
										'id_one' => $id_one,
										'id_two' => $tranche_age_key
									);
								} //$niv2 as $tranche_age_key => $val
						} //$form['select_two'] == 'age'
						$results['serieTwo'] = $serieTwo;
					} //isset($form['select_two']) && $form['select_two'] != '0'
					/* NIVEAU 3 */
					if (isset($form['select_three']) && $form['select_three'] != '0') {
						if ($form['select_three'] == 'reponses_question') {
							$serieThree['name'] = 'Réponses à la question';
							$serieThree['size'] = '94%';
							$serieThree['innerSize'] = '80%';
							$responses = array();
							foreach ($serieTwo['responses'] as $rs) {
								foreach ($responses_totales as $response) {
									switch ($form['select_one']) {
										case 'sexe':
											$compare_one = $response->getEnfantQuiz()->getEnfant()->getSexe();
											break;
										case 'age':
											$age_enfant = date('Y') - $response->getEnfantQuiz()->getEnfant()->getDateNaissance()->format('Y');
											foreach ($tranche_age as $age => $text) {
												if ($age_enfant + 1 >= $age)
													$tranche_age_selected = $age;
											} //$tranche_age as $age => $text
											$compare_one = $tranche_age_selected;
											break;
									} //$form['select_one']
									switch ($form['select_two']) {
										case 'sexe':
											$compare_two = $response->getEnfantQuiz()->getEnfant()->getSexe();
											break;
										case 'age':
											$age_enfant = date('Y') - $response->getEnfantQuiz()->getEnfant()->getDateNaissance()->format('Y');
											foreach ($tranche_age as $age => $text) {
												if ($age_enfant + 1 >= $age)
													$tranche_age_selected = $age;
											} //$tranche_age as $age => $text
											$compare_one = $tranche_age_selected;
											break;
									} //$form['select_two']
									if ($compare_one == $rs['id_one'] && $compare_two == $rs['id_two']) {
										$responses[$rs['id_one']][$rs['id_two']][$response->getResponseChoice()->getId()] = (isset($responses[$rs['id_one']][$rs['id_two']][$response->getResponseChoice()->getId()])) ? $responses[$rs['id_one']][$rs['id_two']][$response->getResponseChoice()->getId()] + 1 : 1;
									} //$compare_one == $rs['id_one'] && $compare_two == $rs['id_two']
								} //$responses_totales as $response
							} //$serieTwo['responses'] as $rs
							foreach ($responses as $id_one => $niv1)
								foreach ($niv1 as $id_two => $rs)
									foreach ($rs as $id_rp => $val) {
										$rp = $em->getRepository('ChildConnectCCSoftBundle:ResponseProposal')->find((int) $id_rp);
										$serieThree['responses'][] = array(
											'name' => $rp->getResponse(),
											'value' => $val,//round((100 * $val) / count($responses_totales), 2)
										);
									} //$rs as $id_rp => $val
						} //$form['select_three'] == 'reponses_question'
						elseif ($form['select_three'] == 'sexe') {
							$serieThree['name'] = 'Sexe';
							$serieThree['size'] = '94%';
							$serieThree['innerSize'] = '80%';
                                                        if (isset($serieTwo['responses'])) {
                                                            foreach ($serieTwo['responses'] as $rs) {
                                                                    $sexes[$rs['id_one']][$rs['id_two']]['M'] = 0;
                                                                    $sexes[$rs['id_one']][$rs['id_two']]['F'] = 0;
                                                            } //$serieTwo['responses'] as $rs
                                                            foreach ($serieTwo['responses'] as $rs) {
                                                                    foreach ($responses_totales as $response) {
                                                                            switch ($form['select_one']) {
                                                                                    case 'age':
                                                                                            $age_enfant = date('Y') - $response->getEnfantQuiz()->getEnfant()->getDateNaissance()->format('Y');
                                                                                            foreach ($tranche_age as $age => $text) {
                                                                                                    if ($age_enfant + 1 >= $age)
                                                                                                            $tranche_age_selected = $age;
                                                                                            } //$tranche_age as $age => $text
                                                                                            $compare_one = $tranche_age_selected;
                                                                                            break;
                                                                                    case 'reponses_question':
                                                                                            $compare_one = $response->getResponseChoice()->getId();
                                                                                            break;
                                                                            } //$form['select_one']
                                                                            switch ($form['select_two']) {
                                                                                    case 'age':
                                                                                            $age_enfant = date('Y') - $response->getEnfantQuiz()->getEnfant()->getDateNaissance()->format('Y');
                                                                                            foreach ($tranche_age as $age => $text) {
                                                                                                    if ($age_enfant + 1 >= $age)
                                                                                                            $tranche_age_selected = $age;
                                                                                            } //$tranche_age as $age => $text
                                                                                            $compare_two = $tranche_age_selected;
                                                                                            break;
                                                                                    case 'reponses_question':
                                                                                            $compare_two = $response->getResponseChoice()->getId();
                                                                                            break;
                                                                            } //$form['select_two']
                                                                            if ($compare_one == $rs['id_one'] && $compare_two == $rs['id_two']) {
                                                                                    if ($response->getEnfantQuiz()->getEnfant()->getSexe() == 'M')
                                                                                            $sexes[$rs['id_one']][$rs['id_two']]['M'] = ($sexes[$rs['id_one']][$rs['id_two']]['M']) ? $sexes[$rs['id_one']][$rs['id_two']]['M'] + 1 : 1;
                                                                                    else
                                                                                            $sexes[$rs['id_one']][$rs['id_two']]['F'] = ($sexes[$rs['id_one']][$rs['id_two']]['F']) ? $sexes[$rs['id_one']][$rs['id_two']]['F'] + 1 : 1;
                                                                            } //$compare_one == $rs['id_one'] && $compare_two == $rs['id_two']
                                                                    } //$responses_totales as $response
                                                            } //$serieTwo['responses'] as $rs
                                                        }
                                                        if (isset($sexes)) {
                                                            foreach ($sexes as $id_one => $niv1) {
                                                                    foreach ($niv1 as $id_two => $sexe) {
                                                                            foreach ($sexe as $id_sexe => $val) {
                                                                                    $serieThree['responses'][] = array(
                                                                                            'name' => ($id_sexe == 'M') ? 'Garçon' : 'Fille',
                                                                                            'value' => $val,//round((100 * $val) / count($responses_totales), 2)
                                                                                    );
                                                                            }
                                                                    }
                                                            }
                                                        }
						} //$form['select_three'] == 'sexe'
							elseif ($form['select_three'] == 'age') {
							$serieThree['name'] = 'Age';
							$serieThree['size'] = '94%';
							$serieThree['innerSize'] = '80%';
							$ages = array();
                                                        if (isset ($serieTwo['responses'])) {
                                                            foreach ($serieTwo['responses'] as $rs) {
                                                                    foreach ($responses_totales as $response) {
                                                                            switch ($form['select_one']) {
                                                                                    case 'sexe':
                                                                                            $compare_one = $response->getEnfantQuiz()->getEnfant()->getSexe();
                                                                                            break;
                                                                                    case 'reponses_question':
                                                                                            $compare_one = $response->getResponseChoice()->getId();
                                                                                            break;
                                                                            } //$form['select_one']
                                                                            switch ($form['select_two']) {
                                                                                    case 'sexe':
                                                                                            $compare_two = $response->getEnfantQuiz()->getEnfant()->getSexe();
                                                                                            break;
                                                                                    case 'reponses_question':
                                                                                            $compare_two = $response->getResponseChoice()->getId();
                                                                                            break;
                                                                            } //$form['select_two']
                                                                            if ($compare_one == $rs['id_one'] && $compare_two == $rs['id_two']) {
                                                                                    $age_enfant = date('Y') - $response->getEnfantQuiz()->getEnfant()->getDateNaissance()->format('Y');
                                                                                    //var_dump('enfant id '.$response->getEnfantQuiz()->getEnfant()->getId());
                                                                                    //var_dump($age_enfant);
                                                                                    foreach ($tranche_age as $age => $text) {
                                                                                            if ($age_enfant + 1 >= $age)
                                                                                                    $tranche_age_selected = $age;
                                                                                    } //$tranche_age as $age => $text
                                                                                    //$serieThree['responses'][] = array('name' => $tranche_age['tranche_age_selected'], 'value' => $val,'id_rp' => $id_rp,'slug' => ($sexe == 'male') ? 'M' : 'F');
                                                                                    //var_dump($tranche_age_selected);
                                                                                    $ages[$rs['id_one']][$rs['id_two']][$tranche_age_selected] = (isset($ages[$rs['id_one']][$rs['id_two']][$tranche_age_selected])) ? $ages[$rs['id_one']][$rs['id_two']][$tranche_age_selected] + 1 : 1;
                                                                            } //$compare_one == $rs['id_one'] && $compare_two == $rs['id_two']
                                                                    } //$responses_totales as $response
                                                            } //$serieTwo['responses'] as $rs
                                                        }
                                                        foreach ($ages as $id_one => $niv2)
								foreach ($niv2 as $id_two => $ages_val)
									foreach ($ages_val as $tranche_age_key => $val) {
										$serieThree['responses'][] = array(
											'name' => $tranche_age[$tranche_age_key],
											'value' => $val,//round((100 * $val) / count($responses_totales), 2)
										);
									} //$ages_val as $tranche_age_key => $val
						} // endif  $form['select_three'] == 'age'
						$results['serieThree'] = $serieThree;
					} //isset($form['select_three']) && $form['select_three'] != '0'
			
					$this->assign('results', $results);
				} //$responses_totales
				else
					$this->assign('nothing', 1);
			} //isset($form['submit'])
		} //$request->getMethod() == 'POST'
		return $this->renderTemplate('ChildConnectCCSoftBundle:Statistique:index.html.twig', array(
			'form' => $form_filtres->createView()
		));
	}
	private function choixStats(Request $request = null)
	{
		$em = $this->getDoctrine()->getManager();
		$q = array();
		$args = array();
		$date_start = date('d-m-Y', strtotime('-1 year', time()));
		$date_end = date('d-m-Y');
		$datas_select_priority = array(
			'0' => 'aucun',
			'reponses_question' => 'Réponses à la question',
			'sexe' => 'Sexe',
			'age' => 'La tranche d\'age'
		);
		if ($request->getMethod() == 'POST') {
			$form = $request->request->get('childconnect_ccsoftbundle_statistiquetype');
			if ($form['association'] != 'all') {
				$association = $em->getRepository('ChildConnectCCSoftBundle:Association')->find((int) $form['association']);
			} //$form['association'] != 'all'
			elseif ($form['association'] == 'all') {
				$association = 0;
			} //$form['association'] == 'all'
			if (isset($form['question'])) {
				$question = $em->getRepository('ChildConnectCCSoftBundle:Question')->find((int) $form['question']);
				$args['filtre_actif_inactif'] = true;
			} //isset($form['question'])
			if (isset($form['enfant_actif_inactif'])) {
				$args['filtre_select_one'] = true;
				$args['datas_select_priority_one'] = $datas_select_priority;
			} //isset($form['enfant_actif_inactif'])
			if (isset($form['select_one']) && $form['select_one'] != '0') {
				if ($form['select_one'] != '0')
					unset($datas_select_priority[$form['select_one']]);
				$args['datas_select_priority_two'] = $datas_select_priority;
				$args['filtre_select_two'] = true;
			} //isset($form['select_one']) && $form['select_one'] != '0'
			if (isset($form['select_two'])) {
				if ($form['select_two'] != '0')
					unset($datas_select_priority[$form['select_two']]);
				$args['datas_select_priority_three'] = $datas_select_priority;
				$args['filtre_select_three'] = true;
			} //isset($form['select_two'])
			if (isset($form['select_three']) && in_array('age', array(
				$form['select_three'],
				$form['select_two'],
				$form['select_one']
			))) {
				$args['select_pas_age'] = true;
			} //isset($form['select_three']) && in_array('age', array( $form['select_three'], $form['select_two'], $form['select_one'] ))
			if ((isset($form['select_pas_age']) && $form['select_pas_age']) || (isset($form['select_three']) && !in_array('age', array(
				$form['select_three'],
				$form['select_two'],
				$form['select_one']
			)))) {
				$args['filtre_dates'] = true;
			} //(isset($form['select_pas_age']) && $form['select_pas_age']) || (isset($form['select_three']) && !in_array('age', array( $form['select_three'], $form['select_two'], $form['select_one'] )))
			if (isset($form['dateStart']) && $form['dateStart'] && isset($form['dateEnd']) && $form['dateEnd']) {
				$date_start = $form['dateStart'];
				$date_end = $form['dateEnd'];
			} //isset($form['dateStart']) && $form['dateStart'] && isset($form['dateEnd']) && $form['dateEnd']
		} //$request->getMethod() == 'POST'
		
		$user = $this->getUser();
		$securityContext = $this->get('security.context');
		$args['user'] = $user;
		 $args['securityContext'] = $securityContext;
		 $args['er'] = $em;
		$form_filtres = $this->createForm(new StatistiqueType($args), NULL, array(
			'action' => $this->generateUrl('statistique'),
			'method' => 'POST',
			'attr' => array(
				'class' => 'choix_stats'
			)
		));
		if (isset($association) && $association) {
			$form_filtres->get('association')->setData($association);
		} //isset($association) && $association
		if (isset($question) && $question) {
			$form_filtres->get('question')->setData($question);
		} //isset($question) && $question
		if (isset($form['enfant_actif_inactif'])) {
			$form_filtres->get('enfant_actif_inactif')->setData($form['enfant_actif_inactif']);
		} //isset($form['enfant_actif_inactif'])
		if (isset($form['select_one']) && $form['select_one'] != '0')
			$form_filtres->get('select_one')->setData($form['select_one']);
		if (isset($form['select_two']))
			$form_filtres->get('select_two')->setData($form['select_two']);
		if (isset($form['select_three'])) {
			$form_filtres->get('select_three')->setData($form['select_three']);
		} //isset($form['select_three'])
		
		if (isset($form['select_pas_age']) && $form['select_pas_age']) {
                    if ($form_filtres->has('select_pas_age')) {
			$form_filtres->get('select_pas_age')->setData($form['select_pas_age']);
                    }
		} //isset($form['select_pas_age']) && $form['select_pas_age']
		if(isset($args['filtre_dates']) && $args['filtre_dates']) {
			$form_filtres->get('dateStart')->setData(date('d-m-Y', strtotime('-6 month')));
			$form_filtres->get('dateEnd')->setData(date('d-m-Y'));
		}
		if (isset($form['dateStart']) && $form['dateStart']) {
                    if ($form_filtres->has('dateStart')) {
			$form_filtres->get('dateStart')->setData($date_start);
                    }
                    if ($form_filtres->has('dateEnd')) {
			$form_filtres->get('dateEnd')->setData($date_end);
                    }
		}
			
		return $form_filtres;
	}
	public function exportExcelAction()
	{
		$form_filtres = $this->createForm(new ExportExcelType(), NULL, array(
			'action' => $this->generateUrl('do_export_excel'),
			'method' => 'POST',
			'attr' => array(
				'class' => 'choix_stats'
			)
		));
		return $this->renderTemplate('ChildConnectCCSoftBundle:Statistique:export-excel.html.twig', array(
			'form' => $form_filtres->createView()
		));
	}
	public function doExportExcelAction(Request $request)
	{
		$em = $this->getDoctrine()->getManager();
		$filename = 'Export_Child_And_Connect-' . date('d-m-Y') . '.xlsx';
		$phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();
		$phpExcelObject->getProperties()->setCreator("ChildAndConnect")->setTitle("Export Child and Connect du " . date('d-m-Y'));
		$table_database = array(
			'Enfant' => 'enfant',
			'Association' => 'association',
			'Enfant-Association' => 'enfant_association',
			'Code Enfant' => 'codeenfant',
			'Enfant-Questionnaire' => 'enfant_quiz',
			'Questionnaire' => 'quiz',
			'Questions' => 'question',
			'Themes des questions' => 'themequestion',
			'Propositions des réponses' => 'responseproposal',
			'Réponses' => 'response',
			'Evénements' => 'event',
			'Lieux' => 'lieu'
		);
		//$phpExcelObject->getActiveSheet()->setTitle('Enfants');
		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		//$sheet_enfants = $phpExcelObject->setActiveSheetIndex(0);
		foreach ($table_database as $name_table => $table) {
			$sheet = $phpExcelObject->createSheet();
			$sheet->setTitle($name_table);
			$cpt = 1;
			$connection = $em->getConnection();
			$statement = $connection->prepare("SELECT * FROM {$table}");
			$statement->execute();
			$results = $statement->fetchAll();
			if(count($results)) {
				$cols = array_keys($results[0]);
				$sheet->fromArray($cols, NULL, 'A' . $cpt++);
				
				foreach ($results as $key => $entity) {
					$sheet->fromArray($entity, NULL, 'A' . $cpt);
					$cpt++;
				} //$results as $key => $entity
			}
		
		} //$table_database as $name_table => $table
		$phpExcelObject->removeSheetByIndex(0);
		
		// create the writer
		$writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel2007');
		// create the response
		$response = $this->get('phpexcel')->createStreamedResponse($writer);
		// adding headers
		$response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
		$response->headers->set('Content-Disposition', 'attachment;filename=' . $filename);
		$response->headers->set('Pragma', 'public');
		$response->headers->set('Cache-Control', 'maxage=1');
		return $response;
	}
}