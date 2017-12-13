<?php
namespace ChildConnect\CCSoftBundle\Service;
use ChildConnect\CCSoftBundle\Entity\Enfant;
use ChildConnect\CCSoftBundle\Entity\Logs;
use ChildConnect\CCSoftBundle\Entity\User;
class LogsService {

    protected $entityManager;

    public function __construct($entityManager) {
        $this->entityManager = $entityManager;
    }

    public function insertLog(User $user,Enfant $enfant,$action,$entity,$fields=NULL) {
        if($action) {
		$log =  new Logs();
		$log->setDate(new \Datetime);
		$log->setUser($user);
		if($enfant)
			$log->setEnfant($enfant);
		$log->setAction($action);
		$log->setEntity($entity);
		if($fields)
			$log->setFields(serialize($fields));
		$this->entityManager->persist($log);
		$this->entityManager->flush();
		}
    }

}