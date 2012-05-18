<?php 

class Model_Repository_Client_Doctrine
	extends Doctrine\ORM\EntityRepository
	implements Model_Repository_Client_Interface
{
	/**
	 * (non-PHPdoc)
	 * @see Model_Repository_Client_Interface::retrieveById()
	 */
	public function retrieveById($id)
	{
		return $this->find($id);
	}
}