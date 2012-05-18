<?php

class Admin_EventController extends Zend_Controller_Action
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
    	
    	$event = $this->_em->getRepository('Model_Event')->retrieveById($id);

    	$this->view->event = $event;
    }
}