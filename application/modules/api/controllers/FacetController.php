<?php

class Api_FacetController extends Czechline_RestAbstractController
{

	/**
	 * @var \Doctrine\ORM\EntityManager
	 */
	protected $_em;
	
	public $requestedMediaType = 'application/xml';
	
    public function init()
    {
        $bootstrap = $this->getInvokeArg('bootstrap');
        $this->_em = $bootstrap->getResource('entityManager');
        
        // api nema zadne layouty
        $this->getHelper('layout')->disableLayout();
        
        // $this->getHelper('viewRenderer')->setNoRender(true); 
        
        // set proper accept (must be set because of error messages in proper media type)
        if(strcmp('application/json', $this->getRequest()->getHeader('Accept')) == 0)
        	$this->setRequestedMediaType('application/json');
    }

    public function indexAction()
    {
    	return $this->onForbiddenListing();
    }
    
    public function getAction()
    {
    	$id = intval($this->getRequest()->getParam('id'));
    	 
    	$facet = $this->_em->getRepository('Model_Facet')->retrieveById($id);
    	 
    	if(is_null($facet))
    		return $this->onNotFound();
    	 
    	// authentication
    	try {
    		$client = $this->getAuthenticatedClient();
    	} catch(Exception $e) {
    		return $this->onAuthenticationFailed();
    	}
    	 
    	// authorization
    	/*
    	if(!$client->getExperiments()->containsKey($id))
    		return $this->onUnauthorized();
    	*/
    	
    	$this->getResponse()->setHeader('Content-type', $this->getRequestedMediaType());
    	$this->view->facet = $facet;
    	$this->view->type =  $this->getRequestedMediaType();
    }
    
    public function postAction()
    {
    	// if no POST data
    	if($this->getRequest()->getRawBody() == false)
    		return $this->onEmptyPost();
    	 
    	// if unsupported Content-type
    	if($this->getRequest()->getHeader('Content-type') != false && strcmp('application/xml', $this->getRequest()->getHeader('Content-type')) != 0)
    		return $this->onUnsupportedContentType();
    	 
    	// if not acceptable
    	if($this->getRequest()->getHeader('Accept') != false
    			&& strcmp('application/xml', $this->getRequest()->getHeader('Accept')) != 0
    			&& strcmp('application/json', $this->getRequest()->getHeader('Accept')) != 0
    			&& strcmp('*/*', $this->getRequest()->getHeader('Accept')) != 0)
    		return $this->onNotAcceptable();
    	
    	libxml_use_internal_errors(true);
    	 
    	$doc = new DOMDocument('1.0', 'utf-8');
    	$loaded = $doc->loadXML($this->getRequest()->getRawBody());
    	 
    	// if malformed XML input
    	if(!$loaded)
    		return $this->onMalformedXml();
    	 
    	// if invalid XML input
    	$valid = $doc->schemaValidate(MODEL_PATH . '/Xml/facet.xsd');
    	if (!$valid)
    		return $this->onInvalidXml();
    	 
    	// authentication
    	try {
    		$client = $this->getAuthenticatedClient();
    	} catch(Exception $e) {
    		return $this->onAuthenticationFailed();
    	}
    	 
    	// authorization
    	/*
    	if(!$client->getExperiments()->containsKey($id))
    		return $this->onUnauthorized();
    	*/
    	 
    	// get key data and create new experiment
    	$experimentId = intval($doc->getElementsByTagName('experimentId')->item(0)->nodeValue);
    	$externalId = intval($doc->getElementsByTagName('externalId')->item(0)->nodeValue);
    	$name = $doc->getElementsByTagName('name')->item(0)->nodeValue;
    	$static = intval($doc->getElementsByTagName('static')->item(0)->nodeValue);
    	    	
    	$experiment = $this->_em->getRepository('Model_Experiment')->retrieveById($experimentId);
    	
    	$facet = new Model_Facet();
    	$facet->setExperiment($experiment);
    	$facet->setExternalId($externalId);
    	$facet->setName($name);
    	$facet->setStatic((bool) $static);
    	
    	$this->_em->persist($facet);
    	$this->_em->flush();
    	
    	
    	$this->getResponse()->setHttpResponseCode(201);
    	$this->getResponse()->setHeader('Content-type', $this->getRequestedMediaType());
    	$this->view->type = $this->getRequestedMediaType();
    	$this->getResponse()->setHeader('Location', WWW_PATH . '/api/facet/' . $facet->getId());
    	
    	// vypiseme vystup se zalozenym experimentem v XML
    	$this->view->facet = $facet;
    }
    
    
    
    public function putAction()
    {
    	// if no POST data
    	if($this->getRequest()->getRawBody() == false)
    		return $this->onEmptyPost();
    	 
    	// if unsupported Content-type
    	if($this->getRequest()->getHeader('Content-type') != false && strcmp('application/xml', $this->getRequest()->getHeader('Content-type')) != 0)
    		return $this->onUnsupportedContentType();
    	 
    	// if not acceptable
    	if($this->getRequest()->getHeader('Accept') != false
    			&& strcmp('application/xml', $this->getRequest()->getHeader('Accept')) != 0
    			&& strcmp('application/json', $this->getRequest()->getHeader('Accept')) != 0
    			&& strcmp('*/*', $this->getRequest()->getHeader('Accept')) != 0)
    		return $this->onNotAcceptable();
    	
    	libxml_use_internal_errors(true);
    	 
    	$doc = new DOMDocument('1.0', 'utf-8');
    	$loaded = $doc->loadXML($this->getRequest()->getRawBody());
    	 
    	// if malformed XML input
    	if(!$loaded)
    		return $this->onMalformedXml();
    	 
    	// if invalid XML input
    	$valid = $doc->schemaValidate(MODEL_PATH . '/Xml/facet.xsd');
    	if (!$valid)
    		return $this->onInvalidXml();
    	 
    	// authentication
    	try {
    		$client = $this->getAuthenticatedClient();
    	} catch(Exception $e) {
    		return $this->onAuthenticationFailed();
    	}
    	 
    	// authorization
    	/*
    	if(!$client->getExperiments()->containsKey($id))
    		return $this->onUnauthorized();
    	*/
    	
    	// get key data and create new facet
    	$id = intval($doc->getElementsByTagName('id')->item(0)->nodeValue);
    	 
    	if($id < 1)
    		return $this->_forward('post'); // kinda POST request in PUT method
    	
    	$facet = $this->_em->getRepository('Model_Facet')->retrieveById($id);
    	 
    	if(is_null($facet))
    		return $this->onNotFound();
    
    	$experimentId = intval($doc->getElementsByTagName('experimentId')->item(0)->nodeValue);
    	$externalId = intval($doc->getElementsByTagName('externalId')->item(0)->nodeValue);
    	$name = $doc->getElementsByTagName('name')->item(0)->nodeValue;
    	$static = intval($doc->getElementsByTagName('static')->item(0)->nodeValue);
    	
    	$experiment = $this->_em->getRepository('Model_Experiment')->retrieveById($experimentId);
    	 
    	$facet->setExperiment($experiment);
    	$facet->setExternalId($externalId);
    	$facet->setName($name);
    	$facet->setStatic((bool) $static);
    	 
    	$this->_em->flush();
    	 
    	$this->getResponse()->setHttpResponseCode(200);
    	$this->getResponse()->setHeader('Content-type', $this->getRequestedMediaType());
    	$this->view->type = $this->getRequestedMediaType();
    	 
    	// vypiseme vystup se zalozenym experimentem v XML
    	$this->view->facet = $facet;
    	
    }
    
    public function deleteAction()
    {
    	$id = intval($this->getRequest()->getParam('id'));
    	$this->getHelper('viewRenderer')->setNoRender(true);
    	
    	if($id < 1)
    	{
    		$this->getResponse()->setHttpResponseCode(400);
    		$this->getResponse()->setHeader('Content-type', $this->getRequestedMediaType());
    		echo $this->getErrorBody('No ID provided for DELETE method.');
    		return;
    	}
    	
    	$facet = $this->_em->getRepository('Model_Facet')->retrieveById($id);
    	
    	if(is_null($facet))
    		return $this->onNotFound();
    	
       	// authentication
    	try {
    		$client = $this->getAuthenticatedClient();
    	} catch(Exception $e) {
    		return $this->onAuthenticationFailed();
    	}
    	
    	// authorization
    	/*
    	if(!$client->getExperiments()->containsKey($id))
    		return $this->onUnauthorized();
    	*/
    	
    	$this->_em->remove($facet);
    	$this->_em->flush();
    	
    	$this->getResponse()->setHttpResponseCode(204); // No Content
    }
}