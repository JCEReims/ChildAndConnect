<?php

namespace ChildConnect\CCSoftBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\ORM\Query\ResultSetMappingBuilder;

/**
 * EnfantRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class EnfantRepository extends EntityRepository
{
	public function findEnfants($securityContext,$orderBy='modifiedAt',$sortBy='DESC',$page=null,$nbrParPage=null,$count = false) {
		$user = $securityContext->getToken()->getUser();
		if($count) {
			$queryBuilder = $this->_em->createQueryBuilder()
			 ->select('COUNT(e)')
			  ->from($this->_entityName, 'e')
			  ->getQuery()->getSingleScalarResult();
			 return (int)$queryBuilder;
			
		}
		
		$queryBuilder = $this->_em->createQueryBuilder()
			 ->select(array('e','a'))
			  ->from($this->_entityName, 'e')
			  ->leftJoin('e.associations','a')
			
			  //->leftJoin('e.photo', 'p')
			  //->groupBy('p.id')
			  ;

		$prefix = 'e.';
		if( in_array($orderBy, array('name_association'))) {
			$prefix = 'a.';
			$orderBy = 'nom';
		}
		$queryBuilder->orderBy($prefix.$orderBy,$sortBy);
		/*if($orderBy) {
			$queryBuilder->orderBy($prefix.$orderBy,$sortBy)
			   	->addOrderBy('p.date','DESC');
		} else {
			$queryBuilder->orderBy('p.date','DESC');
		}
			*/  
		
		if(!$securityContext->isGranted('ROLE_ADMIN')) 
			$queryBuilder->where('a = :association')->setParameter('association', $user->getAssociation());
	
		
		if($page) {
			$r =  $queryBuilder->getQuery()->setFirstResult(($page-1) * ($nbrParPage))->setMaxResults($nbrParPage);
			return new Paginator($r,false);
		}
		else
			$r = $queryBuilder->getQuery()->getResult();
	return $r;
	}
	
	public function existResponses($id) {
		$queryBuilder = $this->_em->createQueryBuilder()
			->select('COUNT(eq)')
			->from($this->_entityName, 'e')
			->leftJoin('e.enfantQuizs','eq')
			->where('eq.enfant = :id_enfant')
			->setParameter(':id_enfant',$id)
			->andWhere('eq.dateResponded IS NOT NULL')
			->getQuery()->getSingleScalarResult();
	
		return (int)$queryBuilder;
	}
	
	public function searchEnfants($s=NULL) {
		if($s) {
			$queryBuilder = $this->_em->createQueryBuilder()
				 ->select(array('e','a','p'))
				  ->from($this->_entityName, 'e')
				  ->leftJoin('e.associations','a')
				  ->leftJoin('e.photo', 'p')
				  ->leftJoin('e.codeEnfant', 'ce')
				  ->orderBy('e.modifiedAt','DESC')
				  ->addOrderBy('p.date','DESC')
				  ;
			$i = 0;
			$parameters = array();
			foreach($s as $str) {
				$queryBuilder->orWhere('e.nom LIKE ?'.$i)
					->orWhere('e.prenom LIKE ?'.$i)
					->orWhere('e.surnom LIKE ?'.$i)
					->orWhere('ce.code LIKE ?'.$i)
					;
				$parameters[] = '%' . $str . '%';
				$i++;
			}
			$queryBuilder->setParameters($parameters);
	
			$r = $queryBuilder->getQuery()->getResult();
			
			return $r;
		} else return false;
	}
	public function isNewEnfant($enfant) {
		$queryBuilder = $this->_em->createQueryBuilder()
			->select('COUNT(eq)')
			->from($this->_entityName, 'e')
			->leftJoin('e.enfantQuizs','eq')
			->where('eq.enfant = :id_enfant')
			->setParameter(':id_enfant',$enfant)
			->andWhere('eq.dateResponded IS NOT NULL')
			->getQuery()->getSingleScalarResult();
	
		return ((int)$queryBuilder) ? false : true;
	}
	public function getEnfantsForStats($dateStart,$dateEnd,$id_association = null) {
		$ids_eq = $this->_getIdsLastEnfantQuiz();
		$queryBuilder = $this->_em->createQueryBuilder();
		$queryBuilder
			->select(array('e','a','eq','r','q'))
			->from($this->_entityName, 'e')
			->leftJoin('e.associations','a')
			->leftJoin('e.enfantQuizs','eq')
			->leftJoin('eq.response','r')
			->leftJoin('r.question','q')
			->where($queryBuilder->expr()->in('r.enfantQuiz',$ids_eq))
			;
			$r = $queryBuilder->getQuery()->getResult();
			
			return $r;
	}
	private function _getIdsLastEnfantQuiz() {
		$rsm = new ResultSetMappingBuilder($this->_em);
		$rsm->addRootEntityFromClassMetadata('ChildConnect\CCSoftBundle\Entity\EnfantQuiz', 'eq');
		$rsm->addJoinedEntityFromClassMetadata('ChildConnect\CCSoftBundle\Entity\Enfant', 'e', 'eq', 'enfant_id', array('id' => 'enfant_id'));
		
		$sql = 'SELECT eq.id, eq.date_responded FROM (SELECT eqs.id,eqs.date_responded , eqs.enfant_id FROM enfant_quiz eqs WHERE eqs.date_responded IS NOT NULL ORDER BY eqs.date_responded DESC )  eq INNER JOIN enfant e ON(e.id = eq.enfant_id)  GROUP BY eq.enfant_id ';
		
		$query = $this->_em->createNativeQuery($sql, $rsm);
		$query_last_ids_enfantquiz = $query->getScalarResult();
		
		foreach($query_last_ids_enfantquiz as $eq)
			$ids_eq[] = $eq['eq_id'];
		return $ids_eq;
	}
	public function findByAssociation($association) {
		$queryBuilder = $this->_em->createQueryBuilder()
			->select('e')
			->from($this->_entityName, 'e')
			->innerJoin('e.associations','a')
			->where('a = :association')
			->setParameter(':association',$association)
			->getQuery()->getResult();
	
		return $queryBuilder;
	}
}