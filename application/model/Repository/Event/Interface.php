<?php 

interface Model_Repository_Event_Interface
{
	
	/**
	 * Retrieves Event by its identifier.
	 * @param integer $id
	 */
	public function retrieveById($id);
	
}