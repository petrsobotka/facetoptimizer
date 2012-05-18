<?php 

class Api_EventControllerTest
	extends ControllerTestCase
{
	
	public function testShouldTriggerAppropriateControllerAndAction()
	{
		$this->dispatch('/api/event');
		$this->assertModule('api');
		$this->assertController('event');
		$this->assertAction('index');
	}
	
	/* -------------------- P O S T --------------------------------------- */
	
	public function testPostRequestShouldTriggerPostAction()
	{
		$this->request->setMethod('POST');
		$this->dispatch('/api/event');
		
		$this->assertModule('api');
		$this->assertController('event');
		$this->assertAction('post');
	}
	
	public function testPostRequestResponseHeaderIsXmlAsDefault()
	{
		$this->request->setMethod('POST');
		$this->dispatch('/api/event');
	
		$this->assertHeader('Content-type');
		$this->assertHeaderContains('Content-type', 'application/xml');
	}
	
	public function testPostRequestResponseHeaderIsXmlWhenRequested()
	{
		$this->request->setMethod('POST');
		$this->request->setHeader("Accept", "application/xml");
		$this->dispatch('/api/event');
		
		$this->assertHeader('Content-type');
		$this->assertHeaderContains('Content-type', 'application/xml');
	}
	
	public function testPostRequestResponseHeaderIsJsonWhenRequested()
	{
		$this->request->setMethod('POST');
		$this->request->setHeader("Accept", "application/json");
		$this->dispatch('/api/event');
		
		$this->assertHeader('Content-type');
		$this->assertHeaderContains('Content-type', 'application/json');
	}
	
	public function testEmptyPostRequestShouldTriggerBadRequestErrorAndContainErrorMessage()
	{
		$this->request->setMethod('POST');
		$this->request->setHeader("Accept", "application/xml");
		$this->dispatch('/api/event');
		
		$this->assertResponseCode(400);
		$this->assertQuery('error');
	}
	
	public function testPostRequestShouldReturnErrorWhenMalformedXmlProvided()
	{
		$this->request->setMethod('POST');
		$this->request->setHeader("Accept", "application/xml");
		$this->request->setHeader("Content-type", "application/xml");
		$this->getRequest()->setRawBody('blbosti');
		$this->dispatch('/api/event');
		
		$this->assertResponseCode(400);
		$this->assertQuery('error');
	}
	
	
	public function testPostRequestShouldReturnErrorWhenInappropriateOrNoRootTagProvided()
	{
		$this->request->setMethod('POST');
		$this->request->setHeader("Content-type", "application/xml");
		$this->getRequest()->setRawBody('<?xml version="1.0" encoding="utf-8"?><ahoj>a</ahoj>');
		$this->dispatch('/api/event');
		
		$this->assertResponseCode(400);
		$this->assertQuery('error');
	}
	
	public function testPostRequestShouldReturnErrorWhenUnsupportedContentTypeProvided()
	{
		$this->request->setMethod('POST');
		$this->request->setHeader("Content-type", "application/json");
		$this->getRequest()->setRawBody('[]');
		$this->dispatch('/api/event');
		
		$this->assertResponseCode(415);
		$this->assertQuery('error');
	}
	
	public function testPostRequestShouldReturnErrorWhenUnsupportedAcceptHeaderProvided()
	{
		$this->request->setMethod('POST');
		$this->request->setHeader("Accept", "text/html");
		$this->getRequest()->setRawBody('<?xml version="1.0" encoding="utf-8"?><facetOptimizer></facetOptimizer>');
		$this->dispatch('/api/event');
		
		$this->assertResponseCode(406);
		$this->assertQuery('error');
	}
	
	public function testPostRequestShouldReturnUnauthorizedErrorWhenNoKeyProvided()
	{
		$this->request->setMethod('POST');
		$this->getRequest()->setRawBody('<?xml version="1.0" encoding="utf-8"?><facetOptimizer><event><name>Pokus</name><url>http://www.ahoj.cz</url><description>popis</description></event></facetOptimizer>');
		$this->dispatch('/api/event');
		
		$this->assertResponseCode(400);
		$this->assertQuery('error');
	}
	
	public function testPostRequestShouldReturnErrorWhenBadApiKeyAndSignatureProvided()
	{
		$this->request->setMethod('POST');
		$this->getRequest()->setRawBody('<?xml version="1.0" encoding="utf-8"?><facetOptimizer><event><name>Pokus</name><url>http://www.ahoj.cz</url><description>popis</description></event></facetOptimizer>');
		$this->dispatch('/api/event?apiKey=a&signature=b');
		
		$this->assertResponseCode(400);
		$this->assertQuery('error');
	}
	
	/* -------------------- G E T --------------------------------------- */
	
	public function testGetRequestWithoutParametersIsRoutedToIndexAction()
	{
		$this->request->setMethod('GET');
		$this->dispatch('/api/event');

		$this->assertModule('api');
		$this->assertController('event');
		$this->assertAction('index');		
	}
	
	public function testGetRequestShouldReturnErrorWhenNoIdSpecified()
	{
		$this->request->setMethod('GET');
		$this->dispatch('/api/event');
		
		$this->assertResponseCode(403);
		$this->assertQuery('error');
	}
	
	/* -------------------- P U T --------------------------------------- */
	
	public function testPutRequestShouldTriggerMethodNotAllowedError()
	{
		$this->request->setMethod('PUT');
		$this->dispatch('/api/event');
	
		$this->assertResponseCode(405);
		$this->assertQuery('error');
	}
	
	/* -------------------- D E L E T E --------------------------------------- */
	
	public function testDeleteRequestShouldTriggerMethodNotAllowedError()
	{
		$this->request->setMethod('DELETE');
		$this->dispatch('/api/event');
	
		$this->assertResponseCode(405);
		$this->assertQuery('error');
	}
}