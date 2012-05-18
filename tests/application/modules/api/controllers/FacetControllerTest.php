<?php 

class Api_FacetControllerTest
	extends ControllerTestCase
{
	
	public function testShouldTriggerAppropriateControllerAndAction()
	{
		$this->dispatch('/api/facet');
		$this->assertModule('api');
		$this->assertController('facet');
		$this->assertAction('index');
	}
	
	/* -------------------- P O S T --------------------------------------- */
	
	public function testPostRequestShouldTriggerPostAction()
	{
		$this->request->setMethod('POST');
		$this->dispatch('/api/facet');
		
		$this->assertModule('api');
		$this->assertController('facet');
		$this->assertAction('post');
	}
	
	public function testPostRequestResponseHeaderIsXmlAsDefault()
	{
		$this->request->setMethod('POST');
		$this->dispatch('/api/facet');
	
		$this->assertHeader('Content-type');
		$this->assertHeaderContains('Content-type', 'application/xml');
	}
	
	public function testPostRequestResponseHeaderIsXmlWhenRequested()
	{
		$this->request->setMethod('POST');
		$this->request->setHeader("Accept", "application/xml");
		$this->dispatch('/api/facet');
		
		$this->assertHeader('Content-type');
		$this->assertHeaderContains('Content-type', 'application/xml');
	}
	
	public function testPostRequestResponseHeaderIsJsonWhenRequested()
	{
		$this->request->setMethod('POST');
		$this->request->setHeader("Accept", "application/json");
		$this->dispatch('/api/facet');
		
		$this->assertHeader('Content-type');
		$this->assertHeaderContains('Content-type', 'application/json');
	}
	
	public function testEmptyPostRequestShouldTriggerBadRequestErrorAndContainErrorMessage()
	{
		$this->request->setMethod('POST');
		$this->request->setHeader("Accept", "application/xml");
		$this->dispatch('/api/facet');
		
		$this->assertResponseCode(400);
		$this->assertQuery('error');
	}
	
	public function testPostRequestShouldReturnErrorWhenMalformedXmlProvided()
	{
		$this->request->setMethod('POST');
		$this->request->setHeader("Accept", "application/xml");
		$this->request->setHeader("Content-type", "application/xml");
		$this->getRequest()->setRawBody('blbosti');
		$this->dispatch('/api/facet');
		
		$this->assertResponseCode(400);
		$this->assertQuery('error');
	}
	
	
	public function testPostRequestShouldReturnErrorWhenInappropriateOrNoRootTagProvided()
	{
		$this->request->setMethod('POST');
		$this->request->setHeader("Content-type", "application/xml");
		$this->getRequest()->setRawBody('<?xml version="1.0" encoding="utf-8"?><ahoj>a</ahoj>');
		$this->dispatch('/api/facet');
		
		$this->assertResponseCode(400);
		$this->assertQuery('error');
	}
	
	public function testPostRequestShouldReturnErrorWhenUnsupportedContentTypeProvided()
	{
		$this->request->setMethod('POST');
		$this->request->setHeader("Content-type", "application/json");
		$this->getRequest()->setRawBody('[]');
		$this->dispatch('/api/facet');
		
		$this->assertResponseCode(415);
		$this->assertQuery('error');
	}
	
	public function testPostRequestShouldReturnErrorWhenUnsupportedAcceptHeaderProvided()
	{
		$this->request->setMethod('POST');
		$this->request->setHeader("Accept", "text/html");
		$this->getRequest()->setRawBody('<?xml version="1.0" encoding="utf-8"?><facetOptimizer></facetOptimizer>');
		$this->dispatch('/api/facet');
		
		$this->assertResponseCode(406);
		$this->assertQuery('error');
	}
	
	public function testPostRequestShouldReturnUnauthorizedErrorWhenNoKeyProvided()
	{
		$this->request->setMethod('POST');
		$this->getRequest()->setRawBody('<?xml version="1.0" encoding="utf-8"?><facetOptimizer><facet><experimentId>5</experimentId><externalId>52</externalId><name>Materiál</name><static>0</static></facet></facetOptimizer>');
		$this->dispatch('/api/facet');
		
		$this->assertResponseCode(400);
		$this->assertQuery('error');
	}
	
	public function testPostRequestShouldReturnErrorWhenBadApiKeyAndSignatureProvided()
	{
		$this->request->setMethod('POST');
		$this->getRequest()->setRawBody('<?xml version="1.0" encoding="utf-8"?><facetOptimizer><facet><experimentId>5</experimentId><externalId>52</externalId><name>Materiál</name><static>0</static></facet></facetOptimizer>');
		$this->dispatch('/api/facet?apiKey=a&signature=b');
		
		$this->assertResponseCode(400);
		$this->assertQuery('error');
	}
	
	/* -------------------- G E T --------------------------------------- */
	
	public function testGetRequestWithoutParametersIsRoutedToIndexAction()
	{
		$this->request->setMethod('GET');
		$this->dispatch('/api/facet');

		$this->assertModule('api');
		$this->assertController('facet');
		$this->assertAction('index');		
	}
	
	public function testGetRequestShouldReturnErrorWhenNoIdSpecified()
	{
		$this->request->setMethod('GET');
		$this->dispatch('/api/facet');
		
		$this->assertResponseCode(403);
		$this->assertQuery('error');
	}
	
	/* -------------------- P U T --------------------------------------- */
	
	public function testPutRequestShouldTriggerPutAction()
	{
		$this->request->setMethod('PUT');
		$this->dispatch('/api/facet');
	
		$this->assertModule('api');
		$this->assertController('facet');
		$this->assertAction('put');
	}
	
	public function testPutRequestResponseHeaderIsXmlAsDefault()
	{
		$this->request->setMethod('PUT');
		$this->dispatch('/api/facet');
	
		$this->assertHeader('Content-type');
		$this->assertHeaderContains('Content-type', 'application/xml');
	}
	
	public function testPutRequestResponseHeaderIsXmlWhenRequested()
	{
		$this->request->setMethod('PUT');
		$this->request->setHeader("Accept", "application/xml");
		$this->dispatch('/api/facet');
	
		$this->assertHeader('Content-type');
		$this->assertHeaderContains('Content-type', 'application/xml');
	}
	
	public function testPutRequestResponseHeaderIsJsonWhenRequested()
	{
		$this->request->setMethod('PUT');
		$this->request->setHeader("Accept", "application/json");
		$this->dispatch('/api/facet');
	
		$this->assertHeader('Content-type');
		$this->assertHeaderContains('Content-type', 'application/json');
	}
	
	public function testEmptyPutRequestShouldTriggerBadRequestErrorAndContainErrorMessage()
	{
		$this->request->setMethod('PUT');
		$this->request->setHeader("Accept", "application/xml");
		$this->dispatch('/api/facet');
	
		$this->assertResponseCode(400);
		$this->assertQuery('error');
	}
	
	public function testPutRequestShouldReturnErrorWhenMalformedXmlProvided()
	{
		$this->request->setMethod('PUT');
		$this->request->setHeader("Accept", "application/xml");
		$this->request->setHeader("Content-type", "application/xml");
		$this->getRequest()->setRawBody('nothing');
		$this->dispatch('/api/facet');
	
		$this->assertResponseCode(400);
		$this->assertQuery('error');
	}
	
	
	public function testPutRequestShouldReturnErrorWhenInappropriateOrNoRootTagProvided()
	{
		$this->request->setMethod('PUT');
		$this->request->setHeader("Content-type", "application/xml");
		$this->getRequest()->setRawBody('<?xml version="1.0" encoding="utf-8"?><ahoj>a</ahoj>');
		$this->dispatch('/api/facet');
	
		$this->assertResponseCode(400);
		$this->assertQuery('error');
	}
	
	public function testPutRequestShouldReturnErrorWhenUnsupportedContentTypeProvided()
	{
		$this->request->setMethod('PUT');
		$this->request->setHeader("Content-type", "application/json");
		$this->getRequest()->setRawBody('[]');
		$this->dispatch('/api/facet');
	
		$this->assertResponseCode(415);
		$this->assertQuery('error');
	}
	
	public function testPutRequestShouldReturnErrorWhenUnsupportedAcceptHeaderProvided()
	{
		$this->request->setMethod('PUT');
		$this->request->setHeader("Accept", "text/html");
		$this->getRequest()->setRawBody('<?xml version="1.0" encoding="utf-8"?><facetOptimizer></facetOptimizer>');
		$this->dispatch('/api/facet');
	
		$this->assertResponseCode(406);
		$this->assertQuery('error');
	}
	
	public function testPutRequestShouldReturnUnauthorizedErrorWhenNoKeyProvided()
	{
		$this->request->setMethod('PUT');
		$this->getRequest()->setRawBody('<?xml version="1.0" encoding="utf-8"?><facetOptimizer><facet><experimentId>5</experimentId><externalId>52</externalId><name>Materiál</name><static>0</static></facet></facetOptimizer>');
		$this->dispatch('/api/facet');
	
		$this->assertResponseCode(400);
		$this->assertQuery('error');
	}
	
	public function testPutRequestShouldReturnErrorWhenBadApiKeyAndSignatureProvided()
	{
		$this->request->setMethod('PUT');
		$this->getRequest()->setRawBody('<?xml version="1.0" encoding="utf-8"?><facetOptimizer><facet><experimentId>5</experimentId><externalId>52</externalId><name>Materiál</name><static>0</static></facet></facetOptimizer>');
		$this->dispatch('/api/facet?apiKey=a&signature=b');
	
		$this->assertResponseCode(400);
		$this->assertQuery('error');
	}
	
	/* -------------------- D E L E T E --------------------------------------- */
	
	public function testDeleteRequestShouldTriggerDeleteAction()
	{
		$this->request->setMethod('DELETE');
		$this->dispatch('/api/facet');
	
		$this->assertModule('api');
		$this->assertController('facet');
		$this->assertAction('delete');
	}
	
	public function testDeleteRequestWithoutIdSpecifiedShouldReturnError()
	{
		$this->request->setMethod('DELETE');
		$this->dispatch('/api/facet');
		
		$this->assertResponseCode(400);
		$this->assertQuery('error');
	}
	
	public function testDeleteRequestWithInvalidIdSpecifiedShouldReturnError()
	{
		$this->request->setMethod('DELETE');
		$this->dispatch('/api/facet?id=654578634534');
	
		$this->assertResponseCode(404);
		$this->assertQuery('error');
	}
}