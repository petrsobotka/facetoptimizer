<?php 

interface Model_Repository_Visitor_Interface
{
	/**
	 * Retrieves Visitor by its ID.
	 * @param integer $id
	 */
	public function retrieveById($id);
	
	/**
	 * Retrieves Visitor by Experiment.
	 * @param Model_Experiment $experiment
	 */
	public function retrieveByExperiment(Model_Experiment $experiment);
	
	/**
	 * Retrieves Visitor by Experiment.
	 * @param Model_Experiment $experiment
	 */
	public function retrieveByExperimentWithEventCount(Model_Experiment $experiment);
}