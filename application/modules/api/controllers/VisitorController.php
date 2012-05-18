<?php

class Api_VisitorController extends Czechline_RestAbstractController
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
    	$id = $this->getRequest()->getParam('id');
    	 
    	$visitor = $this->_em->getRepository('Model_Visitor')->retrieveById($id);
    	 
    	if(is_null($visitor))
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
    	$this->view->visitor = $visitor;
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
    	$valid = $doc->schemaValidate(MODEL_PATH . '/Xml/visitor.xsd');
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
    	$ipv4 = $doc->getElementsByTagName('ipv4')->item(0)->nodeValue;
    	$userAgent = $doc->getElementsByTagName('userAgent')->item(0)->nodeValue;
    	
    	$visitor = new Model_Visitor();
    	$visitor->setId(Czechline_UUID::v4());
    	$visitor->setIpv4($ipv4);
    	$visitor->setUserAgent($userAgent);
    	$visitor->setCreated(time());
    	
    	$this->_em->persist($visitor);
    	$this->_em->flush();
    	
    	
    	$this->getResponse()->setHttpResponseCode(201);
    	$this->getResponse()->setHeader('Content-type', $this->getRequestedMediaType());
    	$this->view->type = $this->getRequestedMediaType();
    	$this->getResponse()->setHeader('Location', WWW_PATH . '/api/visitor/' . $visitor->getId());
    	
    	// print output (representation of created resource)
    	$this->view->visitor = $visitor;
    }    
    
    public function putAction()
    {
  		return $this->onMethodNotAllowed();
    }
    
    public function deleteAction()
    {
    	return $this->onMethodNotAllowed();
    }
}