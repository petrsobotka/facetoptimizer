<?php 

interface Model_Repository_EventType_Interface
{
	
	/**
	 * Retrieves EventType by its identifier.
	 * @param integer $id
	 */
	public function retrieveById($id);
	
}