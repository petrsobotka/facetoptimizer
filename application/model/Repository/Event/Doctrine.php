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
}