<?php 

class Model_Repository_EventType_Doctrine
	extends Doctrine\ORM\EntityRepository
	implements Model_Repository_EventType_Interface
{
	
	/**
	 * (non-PHPdoc)
	 * @see Model_Repository_EventType_Interface::retrieveById()
	 */
	public function retrieveById($id)
	{
		return $this->find($id);
	}
}