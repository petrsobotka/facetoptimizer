<?php

abstract class Czechline_RestAbstractController extends Zend_Rest_Controller
{

    protected function onEmptyPost()
    {
    	$this->getResponse()->setHttpResponseCode(400);
    	$this->getResponse()->setHeader('Content-type', $this->getRequestedMediaType());
    	echo $this->getErrorBody('No content provided in POST method.');
    	$this->getHelper('viewRenderer')->setNoRender(true);
    	return;
    }

    protected function onEmptyPut()
    {
    	$this->getResponse()->setHttpResponseCode(400);
    	$this->getResponse()->setHeader('Content-type', $this->getRequestedMediaType());
    	echo $this->getErrorBody('No content provided in PUT method.');
    	$this->getHelper('viewRenderer')->setNoRender(true);
    	return;
    }
    
    protected function onUnsupportedContentType()
    {
    	$this->getResponse()->setHttpResponseCode(415); // Unsupported Media Type
    	$this->getResponse()->setHeader('Content-type', $this->getRequestedMediaType());
    	echo $this->getErrorBody("Unsupported Content-type. Use application/xml.");
    	$this->getHelper('viewRenderer')->setNoRender(true);
    	return;
    }
    
    protected function onMethodNotAllowed()
    {
    	$this->getResponse()->setHttpResponseCode(405); // Method Not Allowed
    	$this->getResponse()->setHeader('Content-type', $this->getRequestedMediaType());
    	echo $this->getErrorBody("Method not allowed.");
    	$this->getHelper('viewRenderer')->setNoRender(true);
    	return;
    }
    
    protected function onNotAcceptable()
    {
    	$this->getResponse()->setHttpResponseCode(406); // Not Acceptable
    	$this->getResponse()->setHeader('Content-type', $this->getRequestedMediaType());
    	echo $this->getErrorBody("Resource is available only in application/xml or application/json.");
    	$this->getHelper('viewRenderer')->setNoRender(true);
    	return;
    }
    
    protected function onMalformedXml()
    {
    	$this->getResponse()->setHttpResponseCode(400);
    	$this->getResponse()->setHeader('Content-type', $this->getRequestedMediaType());
    	echo $this->getErrorBody(libxml_get_errors());
    	$this->getHelper('viewRenderer')->setNoRender(true);
    	return;
    }
    
    protected function onInvalidXml()
    {
    	$this->getResponse()->setHttpResponseCode(400);
    	$this->getResponse()->setHeader('Content-type', $this->getRequestedMediaType());
    	echo $this->getErrorBody(libxml_get_errors());
    	$this->getHelper('viewRenderer')->setNoRender(true);
    	return;
    }
    
    protected function onAuthenticationFailed()
    {
    	$this->getResponse()->setHttpResponseCode(400);
    	$this->getResponse()->setHeader('Content-type', $this->getRequestedMediaType());
    	echo $this->getErrorBody("Authentication failed. Valid parameters 'apiKey' and 'signature' for each request must be provided!");
    	$this->getHelper('viewRenderer')->setNoRender(true);
    	return;
    }
    
    protected function onForbiddenListing()
    {
    	$this->getResponse()->setHttpResponseCode(403);
    	$this->getResponse()->setHeader('Content-type', $this->getRequestedMediaType());
    	echo $this->getErrorBody("Listing forbidden. Only individual resources can be retrieved specifing 'id' parameter.");
    	$this->getHelper('viewRenderer')->setNoRender(true);
    	return;
    }
    
    protected function onNotFound()
    {
    	$this->getResponse()->setHttpResponseCode(404);
    	$this->getResponse()->setHeader('Content-type', $this->getRequestedMediaType());
    	echo $this->getErrorBody("Resource of specified ID not found.");
    	$this->getHelper('viewRenderer')->setNoRender(true);
    	return;
    }
    
    protected function onUnauthorized()
    {
    	$this->getResponse()->setHttpResponseCode(400);  // there is code 401 Unauthorized, but it is tied with Basic HTTP Authorization schema which we do not use, so 400 is appropriate here
    	$this->getResponse()->setHeader('Content-type', $this->getRequestedMediaType());
    	echo $this->getErrorBody("Unauthorized request.");
    	$this->getHelper('viewRenderer')->setNoRender(true);
    	return;
    }
    
    /**
     * This method verifies authentication information and returns authenticated client 
     * specified in the request.
     * 
     * @return Model_Client
     * @throws Exception
     */
    protected function getAuthenticatedClient()
    {
    	if(is_null($this->getRequest()->getParam('apiKey')) || is_null($this->getRequest()->getParam('signature')))
    	{
    		throw new Exception("Authentication failed.");
    	} else {
    		$apiKey = $this->getRequest()->getParam('apiKey');
    		$signature = $this->getRequest()->getParam('signature');
    	
    		$client = $this->_em->getRepository('Model_Client')->retrieveById($apiKey);
    		if(is_null($client))
    		{
    			throw new Exception("Authentication failed.");
    		}
    	
    		$params = $this->getRequest()->getParams();
    		ksort($params);
    	
    		$payload = '';
    	
    		foreach($params as $key => $param)
    		{
    			if(strcmp($key, 'apiKey') != 0
    					&& strcmp($key, 'signature') != 0
    					&& strcmp($key, 'action') != 0
    					&& strcmp($key, 'controller') != 0
    					&& strcmp($key, 'module') != 0)
    			{
    				$payload .= $param;
    			}
    		}
    	
    	
    		if($this->getRequest()->getRawBody() != false)
    			$payload .= $this->getRequest()->getRawBody();
    	
    		$countedSignature = hash_hmac('sha256', $payload, $client->getSecret());
    	
    		if(strcmp($countedSignature, $signature) != 0)
    			throw new Exception("Authentication failed.");
    		
    		return $client;
    	}
    }
    
    protected function getErrorBody($payload)
    {
    	$message = '';
    	
    	if(is_array($payload))
    	{
    		foreach($payload as $key => $obj)
    		{
    			if($obj instanceof LibXMLError)
    				$message .= $this->getMessageRepr($this->getRequestedMediaType(), $obj->message);
    			if(is_string($obj))
    				$message .= $this->getMessageRepr($this->getRequestedMediaType(), $obj);
    			
    			if(strcmp($this->getRequestedMediaType(), 'application/json') == 0 && $key+1 < count($payload))
    				$message .= ',';
    		}
    	}
    	
    	if(is_string($payload))
    		$message = $this->getMessageRepr($this->getRequestedMediaType(), $payload);
    	
    	if($payload instanceof LibXMLError)
    		$message = $this->getMessageRepr($this->getRequestedMediaType(), $payload->message);
    	
    	if(strcmp($this->getRequestedMediaType(), 'application/xml') == 0)
    		return '<?xml version="1.0" encoding="utf-8"?><facetOptimizer version="1.0"><error>' . $message . '</error></facetOptimizer>'; 
    
    	if(strcmp($this->getRequestedMediaType(), 'application/json') == 0)
    		return '[' . $message . ']';
    }
    
    private function getMessageRepr($type, $message)
    {
    	if(strcmp($type, 'application/json') == 0)
    		return '{ "message": ' . json_encode($message) . '}';
    	if(strcmp($type, 'application/xml') == 0)
    		return '<message>' . htmlspecialchars($message) . '</message>';
    }
    
    protected function setRequestedMediaType($type)
    {
    	$this->requestedMediaType = $type;
    }
    
    protected function getRequestedMediaType()
    {
    	return $this->requestedMediaType;
    }
}