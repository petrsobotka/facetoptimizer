<?php

class Admin_RoleController extends Zend_Controller_Action
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
		$users = $this->_em->getRepository('Model_User')->retrieveAll();
		
		$userIds = array();
		foreach($experiment->getUserBindings() as $binding)
		{
			$userIds[$binding->getUser()->getId()] = true;
		}
		
		$this->view->experiment = $experiment;
		$this->view->users = $users;
		$this->view->userIds = $userIds;
    }
    
    public function addAction()
    {
    	$experimentId = intval($this->getRequest()->getParam('experimentId'));
    	$userId = intval($this->getRequest()->getParam('userId'));
    	$role = $this->getRequest()->getParam('role');
    	
    	$experiment = $this->_em->getRepository('Model_Experiment')->retrieveById($experimentId);
    	$user = $this->_em->getRepository('Model_User')->retrieveById($userId);
    	
    	// zkontrolujeme privilegia soucasne prihlaseneho uzivatele
    	$binding = $this->_em->getRepository('Model_UserBinding')->retrieveByExperimentAndUser($experiment, Czechline_LoggedOnUSer::getUser());
    	if(is_null($binding) || (strcmp('owner', $role) != 0 && strcmp('member', $role) != 0))
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
    	
    	$existingBinding = $this->_em->getRepository('Model_UserBinding')->retrieveByExperimentAndUser($experiment, $user);
    	
    	if(is_null($existingBinding))
    	{
    		$existingBinding = new Model_UserBinding($user, $experiment, $role);
    		$this->_em->persist($existingBinding);
    	} else {
    		$existingBinding->setRole($role);
    	}
    	
    	$this->_em->flush();
    	
    	$this->_redirect('/admin/role?experimentId=' . $experiment->getId());
    }
    
    public function  removeAction()
    {
    	$experimentId = intval($this->getRequest()->getParam('experimentId'));
    	$userId = intval($this->getRequest()->getParam('userId'));
    	 
    	$experiment = $this->_em->getRepository('Model_Experiment')->retrieveById($experimentId);
    	$user = $this->_em->getRepository('Model_User')->retrieveById($userId);
    	 
    	// zkontrolujeme privilegia soucasne prihlaseneho uzivatele
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
    	 
    	$existingBinding = $this->_em->getRepository('Model_UserBinding')->retrieveByExperimentAndUser($experiment, $user);
    	 
    	$this->_em->remove($existingBinding);
    	$this->_em->flush();
    	 
    	$this->_redirect('/admin/role?experimentId=' . $experiment->getId());
    }
}