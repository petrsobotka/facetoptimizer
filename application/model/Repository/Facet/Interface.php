<?php 

interface Model_Repository_Facet_Interface
{
	/**
	 * Retrieves Facet by its ID.
	 * @param integer $id
	 */
	public function retrieveById($id);
	
	/**
	 * Facilitation method. Client doesn't have to remember FacetOptimizer's internal Facet IDs.
	 * Experiment ID and Client's internal ID is sufficient.
	 * @param Model_Experiment $experiment
	 * @param integer $externalId
	 */
	public function retrieveByExperimentAndExternalId(Model_Experiment $experiment, $externalId);
	
	
	/**
	 * Retrieves all facets of an Experiment with additional 'eventCount' field
	 * (the resultset is mixed) and ordered descending by this field. 
	 * @param Model_Experiment $experiment
	 */
	public function retrieveAllByExperimentAndEventTypeOrderedByEventCount(Model_Experiment $experiment, Model_EventType $type);
	
}