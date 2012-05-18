<?php 

interface Model_Repository_FacetValue_Interface
{
	/**
	 * Retrieves facetValue by its ID.
	 * @param integer $id
	 */
	public function retrieveById($id);
	
	/**
	 * Facilitation method. Client doesn't have to remember FacetOptimizer's internal FacetValue IDs.
	 * Experiment ID and Client's internal ID is sufficient.  
	 * @param Model_Experiment $experiment
	 * @param integer $externalId
	 */
	public function retrieveByExperimentAndExternalId(Model_Experiment $experiment, $externalId);
	
	/**
	 * Retrieves FacetValue tree's root by providing corresponding Facet obejct.
	 * @param Model_Facet $facet
	 */
	public function retrieveRootByFacet(Model_Facet $facet);
	
	
	/**
	 * Retrieves all facet values of an Experiment with event count for every one of them.
	 * @param Model_Experiment $experiment
	 */
	public function retrieveByExperimentAndEventTypeWithEventCount(Model_Experiment $experiment, Model_EventType $type);
}