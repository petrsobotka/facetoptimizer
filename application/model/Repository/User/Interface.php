<?php 

interface Model_Repository_User_Interface
{
	/**
	 * Retrieves User by its ID.
	 * @param integer $id
	 */
	public function retrieveById($id);
	
	/**
	 * Retrieves USer by its username.
	 * @param String $username
	 */
	public function retrieveByUsername($username);
}