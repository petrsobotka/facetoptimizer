<?php

class Api_EventController extends Czechline_RestAbstractController
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
    	 
    	$event = $this->_em->getRepository('Model_Event')->retrieveById($id);
    	 
    	if(is_null($event))
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
    	$this->view->event = $event;
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
    	$valid = $doc->schemaValidate(MODEL_PATH . '/Xml/event.xsd');
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
    	$name = $doc->getElementsByTagName('name')->item(0)->nodeValue;
    	$url = $doc->getElementsByTagName('url')->item(0)->nodeValue;
    	$desc = $doc->getElementsByTagName('description')->item(0)->nodeValue;
    	 
    	
    	
    	/* -------------------------------------- */
    	
   	
    	$experimentId = intval($doc->getElementsByTagName('experimentId')->item(0)->nodeValue);
    	$visitorId = $doc->getElementsByTagName('visitorId')->item(0)->nodeValue;
    	$type = $doc->getElementsByTagName('type')->item(0)->nodeValue;
    	
    	$facetValueExternalId = false;
    	if($doc->getElementsByTagName('facetValueExternalId')->item(0) instanceof DOMNode)
    	{
    		$facetValueExternalId = intval($doc->getElementsByTagName('facetValueExternalId')->item(0)->nodeValue);
    	}
    	
    	$facetExternalId = false;
    	if($doc->getElementsByTagName('facetExternalId')->item(0) instanceof DOMNode)
    	{
    		$facetExternalId = intval($doc->getElementsByTagName('facetExternalId')->item(0)->nodeValue);
    	}
    	
    	$timestamp = false;
    	if($doc->getElementsByTagName('timestamp')->item(0) instanceof DOMNode)
    	{
    		$timestamp = intval($doc->getElementsByTagName('timestamp')->item(0)->nodeValue);
    	} else 
    		$timestamp = time();
    	
    	$resultSetSize = false;
    	if($doc->getElementsByTagName('resultSetSize')->item(0) instanceof DOMNode)
    	{
    		$resultSetSize = intval($doc->getElementsByTagName('resultSetSize')->item(0)->nodeValue);
    	}
    	
    	//TODO: zde by mely byt checky: zda visitor existuje atd.
    	$experiment = $this->_em->getRepository('Model_Experiment')->retrieveById($experimentId);
    	$visitor    = $this->_em->getRepository('Model_Visitor')->retrieveById($visitorId);
    	
    	$eventType = false;
    	switch($type)
    	{
    		case 'apply-facet-value':
    			$eventType = $this->_em->getRepository('Model_EventType')->retrieveById(Model_EventType::APPLY_FACET_VALUE);
    			break;
    		case 'cancel-facet-value':
    			$eventType = $this->_em->getRepository('Model_EventType')->retrieveById(Model_EventType::CANCEL_FACET_VALUE);
    			break;
    		case 'cancel-all-facets':
    			$eventType = $this->_em->getRepository('Model_EventType')->retrieveById(Model_EventType::CANCEL_ALL_FACETS);
    			break;
    		case 'help':
    			$eventType = $this->_em->getRepository('Model_EventType')->retrieveById(Model_EventType::HELP);
    			break;
    		case 'sort':
    			$eventType = $this->_em->getRepository('Model_EventType')->retrieveById(Model_EventType::SORT);
    			break;
    		case 'paging':
    			$eventType = $this->_em->getRepository('Model_EventType')->retrieveById(Model_EventType::PAGING);
    			break;
    		case 'select-product':
    			$eventType = $this->_em->getRepository('Model_EventType')->retrieveById(Model_EventType::SELECT_PRODUCT);
    			break;
    		case 'conversion':
    			$eventType = $this->_em->getRepository('Model_EventType')->retrieveById(Model_EventType::CONVERSION);
    			break;
    	}
    	
    	$event = new Model_Event();
    	$event->setEventType($eventType);
    	$event->setExperiment($experiment);
    	$event->setVisitor($visitor);
    	if($facetExternalId != false && $facetValueExternalId != false)
    	{
    		//TODO: zde by mely byt checky: zda faseta patri do daneho experimentu, fasetova hodnota to same
    		$facetValue = $this->_em->getRepository('Model_FacetValue')->retrieveByExperimentAndExternalId($experiment, $facetValueExternalId);
    		$facet      = $this->_em->getRepository('Model_Facet')->retrieveByExperimentAndExternalId($experiment, $facetExternalId);
	    	$event->setFacet($facet);
	    	$event->setFacetValue($facetValue);
    	}
    	$event->setTimestamp($timestamp);
    	
    	if($resultSetSize != false)
    		$event->setResultSetSize($resultSetSize);
    	
    	$this->_em->persist($event);
    	
    	// predchozi volby
    	
    	$formerChoices = array();
    	if($loadedXml->event[0]->formerChoices[0] instanceof SimpleXMLElement)
    	{
    		foreach($loadedXml->event[0]->formerChoices[0] as $choice)
    		{
    			$facetExternalId = (int) $choice->facetExternalId[0];
    			$facetValueExternalId = (int) $choice->facetValueExternalId[0];
    			
				$facetValue = $this->_em->getRepository('Model_FacetValue')->retrieveByExperimentAndExternalId($experiment, $facetValueExternalId);
				$facet      = $this->_em->getRepository('Model_Facet')->retrieveByExperimentAndExternalId($experiment, $facetExternalId);
				
				$formerChoice = new Model_FormerChoice();
				$formerChoice->setEvent($event);
				$formerChoice->setFacet($facet);
				$formerChoice->setFacetValue($facetValue);
				$this->_em->persist($formerChoice);
				
				// pro pouziti ve vypisovaci sablone
				$formerChoices[] = $formerChoice;
    		}
    	}
    	
    	$this->_em->flush();
    	
    	$this->getResponse()->setHttpResponseCode(201);
    	$this->getResponse()->setHeader('Content-type', $this->getRequestedMediaType());
    	$this->view->type = $this->getRequestedMediaType();
    	$this->getResponse()->setHeader('Location', WWW_PATH . '/api/event/' . $event->getId());
    	
    	// vypiseme vystup se zalozenym experimentem ve formatu XML (pouzije se smarty sablona)
    	$this->view->experiment = $experiment;
    	$this->view->visitor = $visitor;
    	$this->view->event = $event;
    	$this->view->formerChoices = $formerChoices;
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