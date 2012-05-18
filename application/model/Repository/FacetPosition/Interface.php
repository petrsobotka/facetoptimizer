<?php 

interface Model_Repository_FacetPosition_Interface
{
	
	/**
	 * Retrieves all FacetPosition objects related to given Experiment and Visitor.
	 * Optimized for heavy usage, for this reason it uses only IDs and not proper Entity objects.
	 * @param integer $experimentId
	 * @param integer $visitorId
	 */
	public function retrieveByExperimentIdAndVisitorId($experimentId, $visitorId);
	
}