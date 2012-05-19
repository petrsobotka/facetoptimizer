<?php

class Api_FacetPositionController extends Czechline_RestAbstractController
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
    
    // Define the custom sort function
    static function custom_sort($a,$b) {
    	return $a['rank']>$b['rank'];
    }

    public function indexAction()
    {
    	// aby se spravne routovaly pozadavky typu api/facet-position?experimentID=1&visitorId=xxxx
    	$this->_forward('get');
    }
    
    public function getAction()
    {
    	
    	// authentication
    	try {
    		$client = $this->getAuthenticatedClient();
    	} catch(Exception $e) {
    		return $this->onAuthenticationFailed();
    	}
    	
    	$experimentId = intval($this->getRequest()->getParam('experimentId'));
    	
    	// TODO: validovat, ze jde o UUIDv4
    	$visitorId = $this->getRequest()->getParam('visitorId');
    	
    	if(is_null($experimentId) || is_null($visitorId))
    	{
    		$this->getHelper('viewRenderer')->setNoRender(true);
    		$this->getResponse()->setHttpResponseCode(400);
    		$this->getResponse()->setHeader('Content-type', $this->getRequestedMediaType());
    		echo $this->getErrorBody('Experiment ID and Visitor ID must be provided.');
    		return;
    	}
    	
    	$experiment =  $this->_em->getRepository('Model_Experiment')->retrieveById($experimentId);
    	
    	if($experiment->isRunning())
    	{
    		/*
    		 * Experiment is running, we serve random but consistent facet order to incoming visitors. 
    		 */
    		
    		
	    	$positions = $this->_em->getRepository('Model_FacetPosition')->retrieveByExperimentIdAndVisitorId($experimentId, $visitorId);
	    	
	    	if(count($positions) == 0)
	    	{
	    		// pro tohoto visitora a experiment jeste nebyly vygenerovany
	    		
	    		$experiment = $this->_em->getRepository('Model_Experiment')->retrieveById($experimentId);
	    		$visitor = $this->_em->getRepository('Model_Visitor')->retrieveById($visitorId);
	    		
	    		// pokud je zadany identifikator visitora nebo experimentu neplatny
	    		if(is_null($visitor) || is_null($experiment))
	    		{
	    			$this->getResponse()->setHttpResponseCode(404);
	    			$this->getResponse()->setHeader('Content-type', 'application/xml');
	    			echo '<?xml version="1.0" encoding="utf-8"?><facetOptimizer><error>Invalid Experiment ID or Visitor ID provided.</error></facetOptimizer>';
	    			$this->getHelper('viewRenderer')->setNoRender(true);
	    			return;
	    		}
	
	    		$randomizedArray;
	    		$i = 0;
	    		foreach($experiment->getFacets() as $facet)
	    		{
	    			if($facet->isStatic()) // pokud je faseta staticka, nebudeme ji vubec randomizovat
	    				continue;
	    			$randomizedArray[$i]['facet'] = $facet;
	    			$randomizedArray[$i]['rank'] = mt_rand(0, 1000);
	    			//echo $facet->getName() . " | " . $randomizedArray[$i]['rank'] . "<br />\n";
	    			$i++;
	    		}
	    		
	    		//echo "<br /><br />\n";
	    		
	    	     // Sort the multidimensional array by random number
	     		usort($randomizedArray, array("Api_FacetPositionController", "custom_sort"));
	    		
	    		for($i = 0; $i < count($randomizedArray); $i++)
	    		{
	    			//echo $randomizedArray[$i]['facet']->getName() . " | " . $randomizedArray[$i]['rank'] . "<br />\n";
	    			$pos = new Model_FacetPosition();
					$pos->setPosition($i);
					$pos->setExperiment($experiment);
					$pos->setVisitor($visitor);
					$pos->setFacet($randomizedArray[$i]['facet']);
					
					$this->_em->persist($pos);
	    		}
	    		$this->_em->flush();
	    		
	    		$positions = $this->_em->getRepository('Model_FacetPosition')->retrieveByExperimentIdAndVisitorId($experimentId, $visitorId);
	    		
	    	}
    	
    	} else {
    		/*
    		 * experiment is paused, we serve discovered optimal facet order to visitors
    		 */
    		
    		$eventType = $this->_em->getRepository('Model_EventType')->retrieveById(Model_EventType::APPLY_FACET_VALUE);
    		
    		$facets = $this->_em->getRepository('Model_Facet')->retrieveAllByExperimentAndEventTypeOrderedByEventCount($experiment, $eventType);
    		
    		$positions = array();
    		$i = 0;
    		foreach($facets as $row)
    		{
    			$facet = $row[0];
    			
    			if(!$facet->isStatic())
    			{
	    			$fp = new Model_FacetPosition();
	    			$fp->setExperiment($experiment);
	    			$fp->setFacet($facet);
	    			$fp->setVisitor(new Model_Visitor()); // mock
	    			$fp->setPosition($i);
	    			
	    			
	    			$positions[] = $fp;
	    			$i++;
    			}
    		}
    	}
    	
    	$this->getResponse()->setHeader('Content-type', $this->getRequestedMediaType());
    	
    	$this->view->positions = $positions;
    	$this->view->experimentId = $experimentId;
    	$this->view->visitorId = $visitorId;
    	$this->view->type =  $this->getRequestedMediaType();
    }
    
    public function postAction()
    {
    	return $this->onMethodNotAllowed();
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