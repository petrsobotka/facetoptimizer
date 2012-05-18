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
}