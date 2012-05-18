<?php 

class Model_Repository_Facet_Doctrine
	extends Doctrine\ORM\EntityRepository
	implements Model_Repository_Facet_Interface
{
	/**
	 * (non-PHPdoc)
	 * @see Model_Repository_Facet_Interface::retrieveById()
	 */
	public function retrieveById($id)
	{
		return $this->find($id);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see Model_Repository_Facet_Interface::retrieveByExperimentAndExternalId()
	 */
	public function retrieveByExperimentAndExternalId(Model_Experiment $experiment, $externalId)
	{
		$query = $this->_em->createQuery("SELECT f FROM Model_Facet f WHERE f.experiment = :experiment AND f.externalId = :externalId");
		$query->setParameter('experiment', $experiment);
		$query->setParameter('externalId', $externalId);
		return $query->getSingleResult();
	}
	
	public function retrieveAllByExperimentAndEventTypeOrderedByEventCount(Model_Experiment $experiment, Model_EventType $type)
	{
		//$query = $this->_em->createQuery("SELECT f, COUNT(e.id) eventCount FROM Model_Facet f JOIN f.events e WHERE f.experiment = :experiment GROUP BY f.id ORDER BY eventCount DESC");
		$query = $this->_em->createQuery("SELECT f, COUNT(e.id) eventCount FROM Model_Facet f JOIN f.events e JOIN e.eventType t WHERE f.experiment = :experiment AND t = :type GROUP BY f.id ORDER BY eventCount DESC");
		$query->setParameter('experiment', $experiment);
		$query->setParameter('type', $type);
		return $query->getResult();
	}
}