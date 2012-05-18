<?php 

class Model_Repository_FacetValue_Doctrine
	extends Doctrine\ORM\EntityRepository
	implements Model_Repository_FacetValue_Interface
{
	
	/**
	 * (non-PHPdoc)
	 * @see Model_Repository_FacetValue_Interface::retrieveById()
	 */
	public function retrieveById($id)
	{
		return $this->find($id);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see Model_Repository_FacetValue_Interface::retrieveByExperimentAndExternalId()
	 */
	public function retrieveByExperimentAndExternalId(Model_Experiment $experiment, $externalId)
	{
		$query = $this->_em->createQuery("SELECT fv FROM Model_FacetValue fv JOIN fv.facet f WHERE f.experiment = :experiment AND fv.externalId = :externalId");
		$query->setParameter('experiment', $experiment);
		$query->setParameter('externalId', $externalId);
		return $query->getSingleResult();
	}
	
	/**
	 * (non-PHPdoc)
	 * @see Model_Repository_FacetValue_Interface::retrieveRootByFacet()
	 */
	public function retrieveRootByFacet(Model_facet $facet)
	{
		$query = $this->_em->createQuery("SELECT fv FROM Model_FacetValue fv WHERE fv.lft = 1 AND fv.facet = :facet");
		$query->setParameter('facet', $facet);
		
		return $query->getSingleResult();
	}
	
	public function retrieveByExperimentAndEventTypeWithEventCount(Model_Experiment $experiment, Model_EventType $type)
	{
		// v.lft != 1 // because we don't want to select FacetValue roots...
		$query = $this->_em->createQuery("SELECT v.id, COUNT(e.id) eventCount FROM Model_FacetValue v JOIN v.facet f LEFT JOIN v.events e JOIN e.eventType t WHERE f.experiment = :experiment AND t = :type AND v.lft != 1 GROUP BY v.id");
		$query->setParameter('experiment', $experiment);
		$query->setParameter('type', $type);
		return $query->getResult();
	}
}