<?php 

interface Model_Repository_Client_Interface
{
	
	/**
	 * Retrieves Client by its identifier.
	 * @param string $id
	 */
	public function retrieveById($id);
	
}