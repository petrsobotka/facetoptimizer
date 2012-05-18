<?php 

class Model_Repository_FacetPosition_Doctrine
	extends Doctrine\ORM\EntityRepository
	implements Model_Repository_FacetPosition_Interface
{
	public function retrieveByExperimentIdAndVisitorId($experimentId, $visitorId)
	{
		$query = $this->_em->createQuery("SELECT fp FROM Model_FacetPosition fp JOIN fp.experiment e JOIN fp.visitor v WHERE e.id = :experimentId AND v.id = :visitorId ORDER BY fp.position ASC");
		$query->setParameter('experimentId', $experimentId);
		$query->setParameter('visitorId', $visitorId);
		return $query->getResult();
	}
}