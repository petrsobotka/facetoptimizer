<?php 

interface Model_Repository_Experiment_Interface
{
	
	/**
	 * Retrieves Experiment by its identifier.
	 * @param integer $id
	 */
	public function retrieveById($id);
	
	/**
	 * Returns all Experiments
	 */
	public function retrieveAll();
	
	/**
	 * Retrieves all Experiments each with facets and event count and total count. 
	 */
	public function retrieveAllWithEventCountByUser(Model_User $user);
	
}