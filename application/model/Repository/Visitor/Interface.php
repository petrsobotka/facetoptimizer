<?php 

interface Model_Repository_Visitor_Interface
{
	/**
	 * Retrieves Visitor by its ID.
	 * @param integer $id
	 */
	public function retrieveById($id);
}