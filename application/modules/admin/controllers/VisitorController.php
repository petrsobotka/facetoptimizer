<?php

class Admin_VisitorController extends Zend_Controller_Action
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
		$experimentId = intval($this->getRequest()->getParam('experimentId'));
		
		$experiment = $this->_em->getRepository('Model_Experiment')->retrieveById($experimentId);
		
		$visitors = $this->_em->getRepository('Model_Visitor')->retrieveByExperimentWithEventCount($experiment);
		
		// instancujeme firstEvent jako objekt DateTime
		foreach($visitors as $key => $row)
		{
			$dt = new DateTime();
			$dt->setTimestamp($row['firstEvent']);
			$visitors[$key]['firstEvent'] = $dt;
		}
		
		$this->view->experiment = $experiment;
		$this->view->visitors = $visitors;
    }
    
    public function eventsAction()
    {
    	$visitorId = $this->getRequest()->getParam('visitorId');
    	$experimentId = $this->getRequest()->getParam('experimentId');
    	
    	$visitor = $this->_em->getRepository('Model_Visitor')->retrieveById($visitorId);
    	$experiment = $this->_em->getRepository('Model_Experiment')->retrieveById($experimentId);
    	
    	$this->view->visitor = $visitor;
    	$this->view->experiment = $experiment;
    	
    }
}