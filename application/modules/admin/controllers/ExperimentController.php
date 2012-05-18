<?php

class Admin_ExperimentController extends Zend_Controller_Action
{

	/**
	 * @var \Doctrine\ORM\EntityManager
	 */
	private $_em;
	
    public function init()
    {
        $bootstrap = $this->getInvokeArg('bootstrap');
        $this->_em = $bootstrap->getResource('entityManager');
    }

    public function indexAction()
    {
    	$id = intval($this->getRequest()->getParam('id'));
    	
    	$eventTypeId  = intval($this->getRequest()->getParam('type'));
    	if(empty($eventTypeId))
    		$eventTypeId = 1;
    	
    	$experiment = $this->_em->getRepository('Model_Experiment')->retrieveById($id);
    	$eventType = $this->_em->getRepository('Model_EventType')->retrieveById($eventTypeId);
    	
    	$facetValueTrees = array();
    	$facetValueRepo = $this->_em->getRepository('Model_FacetValue');
    	$nestedSetService = new Model_Service_NestedSet_Standard($this->_em, 'Model_FacetValue');
    	
    	foreach($experiment->getFacets() as $facet)
    	{
    		try {
    			$root = $facetValueRepo->retrieveRootByFacet($facet);
	    		$facetValueTrees[$facet->getId()] = $nestedSetService->fetchTreeByRoot($root);
    		} catch (\Doctrine\ORM\NoResultException $e){
    			
    		}
    	}
    	
    	// vytahneme fasety rovnou s eventCountem
    	$facets = $this->_em->getRepository('Model_Facet')->retrieveAllByExperimentAndEventTypeOrderedByEventCount($experiment, $eventType);
    	
    	// vytahneme hodnoty rovnou s eventCountem
    	$values = $this->_em->getRepository('Model_FacetValue')->retrieveByExperimentAndEventTypeWithEventCount($experiment, $eventType);
    		
    	// prekopeme hodnoty do ID indexed arraye
    	$idIndexedValueCounts = array();
    	foreach($values as $row)
    	{
    		$idIndexedValueCounts[$row['id']] = $row['eventCount'];
    	}
    	
    	// spocitame celkovy pocet udalosti (rychlejsi, nez se ptat opet do DB)
    	$totalEventsCount = 0;
    	foreach($facets as $row)
    	{
    		$totalEventsCount += $row['eventCount'];
    	}
    	
    	$this->view->facets = $facets;
    	$this->view->valueCounts = $idIndexedValueCounts;
    	$this->view->totalEventsCount = $totalEventsCount;
    	$this->view->experiment = $experiment;
    	$this->view->facetValueTrees = $facetValueTrees;
    }
    
    public function newAction()
    {
    	if(!$this->getRequest()->isPost())
    	{
    		return;
    	}
    	
    	$name = $this->getRequest()->getParam('name');
    	$url = $this->getRequest()->getParam('url');
    	$desc = $this->getRequest()->getParam('desc');
    	
    	$exp = new Model_Experiment();
    	$exp->setName($name);
    	$exp->setUrl($url);
    	$exp->setDescription($desc);
    	
    	$this->_em->persist($exp);
    	$this->_em->flush(); // nutne, protoze binding ma identity throug entity a potrebuje znat pridelen ID experimentu
    	
    	$binding = new Model_UserBinding(Czechline_LoggedOnUSer::getUser(), $exp, "owner");
    	
    	$this->_em->persist($binding);
    	$this->_em->flush();
    	
    	$this->_redirect('/admin');
    }
    
    public function deleteAction()
    {
    	$id = intval($this->getRequest()->getParam('id'));
    	
    	$experiment = $this->_em->getRepository('Model_Experiment')->retrieveById($id);
    	
    	if(is_null($experiment))
    	{
    		$this->getResponse()->setHttpResponseCode(400);
    		echo "Bad Request";
    		$this->getHelper('viewRenderer')->setNoRender();
    		$this->getHelper('layout')->disableLayout();
    		return;
    	}
    	
    	$binding = $this->_em->getRepository('Model_UserBinding')->retrieveByExperimentAndUser($experiment, Czechline_LoggedOnUSer::getUser());
    	
    	if(is_null($binding))
    	{
    		$this->getResponse()->setHttpResponseCode(400);
    		echo "Bad Request";
    		$this->getHelper('viewRenderer')->setNoRender();
    		$this->getHelper('layout')->disableLayout();
    		return;
    	}
    	
    	if(strcmp($binding->getRole(), 'owner') != 0)
    	{
    		return $this->_forward('unauthorized', 'auth', 'admin');
    	}
    	
    	$this->_em->remove($binding);
    	$this->_em->remove($experiment);
    	$this->_em->flush();
    	$this->_redirect('/admin');
    }
}