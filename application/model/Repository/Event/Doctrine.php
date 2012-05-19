<?php 

class Model_Repository_Event_Doctrine
	extends Doctrine\ORM\EntityRepository
	implements Model_Repository_Event_Interface
{
	
	/**
	 * (non-PHPdoc)
	 * @see Model_Repository_Event_Interface::retrieveById()
	 */
	public function retrieveById($id)
	{
		return $this->find($id);
	}
	
	public function retrieveAllEventCountByeperiment(Model_Experiment $experiment)
	{
		$query = $this->_em->createQuery("SELECT t, COUNT(e.id) eventCount FROM Model_EventType t JOIN t.events e WHERE e.experiment = :experiment GROUP BY t.id");
		$query->setParameter('experiment', $experiment);
		return $query->getResult();
	}
}