<?php
namespace ChildConnect\CCSoftBundle\Service;
use ChildConnect\CCSoftBundle\Entity\Enfant;


class ModificationEnfantService {

    protected $entityManager;

    public function __construct($entityManager) {
        $this->entityManager = $entityManager;
    }

    public function modifEnfant($enfant) {
        /*$modifEnfant = new ModificationEnfant();
		$modifEnfant->setEnfant($enfant);
		$modifEnfant->setModifiedAt(new \Datetime);
		$this->entityManager->persist($modifEnfant);
		$this->entityManager->flush();*/
		$enfant->setModifiedAt(new \Datetime);
		$this->entityManager->persist($enfant);
		$this->entityManager->flush();
		
    }

}