<?php 

interface Model_Repository_UserBinding_Interface
{
	/**
	 * Retrieves Binding by Experiment and User.
	 * @param integer $id
	 */
	public function retrieveByExperimentAndUser(Model_Experiment $experiment, Model_User $user);
}