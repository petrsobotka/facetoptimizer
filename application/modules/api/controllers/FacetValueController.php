<?php

class Api_FacetValueController extends Czechline_RestAbstractController
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
    	
    	$facetValue = $this->_em->getRepository('Model_FacetValue')->retrieveById($id);
    	
    	if(is_null($facetValue))
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
    	$this->view->facetValue = $facetValue;
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
    	$valid = $doc->schemaValidate(MODEL_PATH . '/Xml/facet-value.xsd');
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
    	
    	$facetId = intval($doc->getElementsByTagName('facetId')->item(0)->nodeValue);
    	$externalId = intval($doc->getElementsByTagName('externalId')->item(0)->nodeValue);
    	$name = $doc->getElementsByTagName('name')->item(0)->nodeValue;
    	
    	$parent = false;
    	if($doc->getElementsByTagName('parentId')->item(0) instanceof DOMNode)
    	{
    		$parent = intval($doc->getElementsByTagName('parentId')->item(0)->nodeValue);
    	}

    	$facet = $this->_em->getRepository('Model_Facet')->retrieveById($facetId);
    	
    	$facetValue = new Model_FacetValue();
    	$facetValue->setFacet($facet);
    	$facetValue->setExternalId($externalId); //TODO: zde by mela byt kontrola, ze jeste neexistuje v tomto experimentu facetova hodnota se stejnym id
    	$facetValue->setName($name);
    	
    	$service = new Model_Service_NestedSet_Standard($this->_em, 'Model_FacetValue');
    	
    	$parentValue = false;
    	if($parent != false)
    	{
    		$parentValue = $this->_em->getRepository('Model_FacetValue')->retrieveById($parent);
    		//TODO: zde by to chtelo nejaky check, zda vytazena FacetValue opravdu patri do tohoto experimentu a do teto fasety, neboli integrity chceck
    	} else {
    		try {
    			$parentValue = $this->_em->getRepository('Model_FacetValue')->retrieveRootByFacet($facet);
    		} catch(\Doctrine\ORM\NoResultException $e){
    			// root jeste neexistuje, zalozime ho
    			$root = new Model_FacetValue();
    			$root->setFacet($facet);
    			$root->setExternalId(0);
    			$root->setName('root');
    			$service->createRoot($root);
    			$parentValue = $root;
    		}
    	}
    	
    	// servica si sama vse ulozi (neni treba volat persist a flush)
    	$service->addChild($parentValue, $facetValue);
    	
    	$this->getResponse()->setHttpResponseCode(201);
    	$this->getResponse()->setHeader('Content-type', $this->getRequestedMediaType());
    	$this->getResponse()->setHeader('Location', WWW_PATH . '/api/facet-valeu/' . $facetValue->getId());
    	
    	// vypiseme vystup se zalozenym experimentem v XML
    	$this->view->facetValue = $facetValue;
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
    	$valid = $doc->schemaValidate(MODEL_PATH . '/Xml/facet-value.xsd');
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
    	
    	$facetValue = $this->_em->getRepository('Model_FacetValue')->retrieveById($id);
    	
    	if(is_null($facetValue))
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
    	
    	$service = new Model_Service_NestedSet_Standard($this->_em, 'Model_FacetValue');
    	$node = $service->wrap($facetValue);
    	
    	$node->delete(); // calls internally EntittyManager->flush()
    	
    	$this->getResponse()->setHttpResponseCode(204); // No Content
    }
}