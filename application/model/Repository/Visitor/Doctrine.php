<?php 

class Model_Repository_Visitor_Doctrine
	extends Doctrine\ORM\EntityRepository
	implements Model_Repository_Visitor_Interface
{
	/**
	 * (non-PHPdoc)
	 * @see Model_Repository_Visitor_Interface::retrieveById()
	 */
	public function retrieveById($id)
	{
		return $this->find($id);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see Model_Repository_Visitor_Interface::retrieveByExperiment()
	 */
	public function retrieveByExperiment(Model_Experiment $experiment)
	{
		$query = $this->_em->createQuery("SELECT v FROM Model_Visitor v JOIN v.events e WHERE e.experiment = :experiment");
		$query->setParameter('experiment', $experiment);
		return $query->getResult();
	}
	
	/**
	 * (non-PHPdoc)
	 * @see Model_Repository_Visitor_Interface::retrieveByExperimentWithEventCount()
	 */
	public function retrieveByExperimentWithEventCount(Model_Experiment $experiment)
	{
		$query = $this->_em->createQuery("SELECT v, COUNT(e) eventCount, e.timestamp firstEvent FROM Model_Visitor v JOIN v.events e WHERE e.experiment = :experiment GROUP BY v.id ORDER BY firstEvent");
		$query->setParameter('experiment', $experiment);
		return $query->getResult();
	}
}