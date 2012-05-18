<?php

class Admin_ClientController extends Zend_Controller_Action
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
		
		$this->view->experiment = $experiment;
    }
    
    public function newAction()
    {
    	
    	$experimentId = intval($this->getRequest()->getParam('experimentId'));
    	if(!$this->getRequest()->isPost())
    	{
    		$this->view->experimentId = $experimentId;
    		return;
    	}
    	
    	$name = $this->getRequest()->getParam('name');
    	$experiment = $this->_em->getRepository('Model_Experiment')->retrieveById($experimentId);
    	
    	$client = new Model_Client(md5(mt_rand()), md5(mt_rand()), $name);
    	
    	$this->_em->persist($client);
    	
    	$client->getExperiments()->add($experiment);

    	$this->_em->flush($client);
    	
    	$this->_redirect('/admin/client?experimentId=' . $experimentId);
    	
    }
    
    public function deleteAction()
    {
    	$id = $this->getRequest()->getParam('id');
    	$experimentId = intval($this->getRequest()->getParam('experimentId'));
    	 
    	$client = $this->_em->getRepository('Model_Client')->retrieveById($id);
    	 
    	$this->_em->remove($client);
    	$this->_em->flush();
    	 
    	$this->_redirect('/admin/client?experimentId=' . $experimentId);
    }
}