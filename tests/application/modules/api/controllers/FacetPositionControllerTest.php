<?php 

class Api_FacetPositionControllerTest
	extends ControllerTestCase
{
	
	public function testShouldTriggerAppropriateControllerAndAction()
	{
		$this->dispatch('/api/facet-position');
		$this->assertModule('api');
		$this->assertController('facet-position');
		$this->assertAction('get');
	}
	
	/* -------------------- P O S T --------------------------------------- */
	
	public function testPostRequestShouldTriggerMethodNotAllowedError()
	{
		$this->request->setMethod('POST');
		$this->dispatch('/api/facet-position');
	
		$this->assertResponseCode(405);
		$this->assertQuery('error');
	}
	
	/* -------------------- G E T --------------------------------------- */
	
	public function testGetRequestWithoutParametersIsRoutedToGetAction()
	{
		$this->request->setMethod('GET');
		$this->dispatch('/api/facet-position');

		$this->assertModule('api');
		$this->assertController('facet-position');
		$this->assertAction('get');
	}
	
	public function testGetRequestShouldReturnErrorWhenNoIdSpecified()
	{
		$this->request->setMethod('GET');
		$this->dispatch('/api/facet-position');
		
		$this->assertResponseCode(400);
		$this->assertQuery('error');
	}
	
	/* -------------------- P U T --------------------------------------- */
	
	public function testPutRequestShouldTriggerMethodNotAllowedError()
	{
		$this->request->setMethod('PUT');
		$this->dispatch('/api/facet-position');
	
		$this->assertResponseCode(405);
		$this->assertQuery('error');
	}
	
	/* -------------------- D E L E T E --------------------------------------- */
	
	public function testDeleteRequestShouldTriggerMethodNotAllowedError()
	{
		$this->request->setMethod('DELETE');
		$this->dispatch('/api/facet-position');
	
		$this->assertResponseCode(405);
		$this->assertQuery('error');
	}
}