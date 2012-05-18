<?php

class Admin_IndexController extends Zend_Controller_Action
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
    	
    	$experiments = $this->_em->getRepository('Model_Experiment')->retrieveAllWithEventCountByUser(Czechline_LoggedOnUSer::getUser());    	
    	
    	$maxEvents = 0;
    	foreach($experiments as  $row)
    	{
    		if($row['eventCount'] > $maxEvents)
    			$maxEvents = $row['eventCount'];
    	}
    	if($maxEvents == 0) // ochrnaa proti deleni nulou
    		$maxEvents = 1; 
    	
    	$this->view->maxEvents = $maxEvents;
    	$this->view->experiments = $experiments;
    }
    
    /**
     * Metoda vygeneruje Doctrine Entity z databazovych tabulek. 
     * Jedna se o pomocnou funkcinlaitu a na tomto miste nema co delat. 
     */
    public function generateModelAction()
    {
    	$this->_em->getConfiguration()->setMetadataDriverImpl( new \Doctrine\ORM\Mapping\Driver\DatabaseDriver($this->_em->getConnection()->getSchemaManager()));
		$cmf = new \Doctrine\ORM\Tools\DisconnectedClassMetadataFactory();
		$cmf->setEntityManager($this->_em);
		$metadata = $cmf->getAllMetadata();
		$cme = new \Doctrine\ORM\Tools\Export\ClassMetadataExporter(); 
		
		$generator = new \Doctrine\ORM\Tools\EntityGenerator(); 
		$generator->setGenerateAnnotations(true); 
		$generator->setGenerateStubMethods(true); 
		$generator->setRegenerateEntityIfExists(false); 
		$generator->setUpdateEntityIfExists(true); 
		//$generator->generate($classes, '/path/to/generate/entities');
		
		$exporter = $cme->getExporter('annotation', APPLICATION_PATH . '/../db/generated-entities');
		$exporter->setEntityGenerator($generator);
		$exporter->setMetadata($metadata);
		$exporter->export();
		
		$this->_helper->viewRenderer->setNoRender();
    }
    
    /**
     * Tahle utilit funkce by tu samozrejme nemela byt.
     * Musim vymyslet, kam ji presne dam.
     */
    public function addUserAction()
    {	
    	if(!$this->getRequest()->isPost())
    	{
    		return;
    	}
    	
    	$user = Czechline_LoggedOnUSer::getUser();
    	
    	if(!$user->isSuperuser())
    	{
    		return $this->_forward('unauthorized', 'auth', 'admin');
    	}
    	
    	$email = $this->getRequest()->getParam('email');
    	$pass = $this->getRequest()->getParam('pass');
    	$passConf = $this->getRequest()->getParam('passagain');
    	$name = $this->getRequest()->getParam('name');
    	
    	if(strlen($name) < 2 || strlen($email) < 2)
    	{
    		$this->view->message = 'Name and email must be at least 2 characters long';
    		return;
    	}
    	
    	if(strcmp($pass, $passConf) != 0)
    	{
    		$this->view->message = 'Password confirmation mismatch!' ;
    		return;
    	}
    	
    	$user = new Model_User();
    	$user->setName($name);
    	$user->setEmail($email);
    	$user->setSalt(substr(md5(mt_rand()), 0, 16));
    	$user->setPassword(hash_hmac("sha256", $pass, $user->getSalt()));
    	$user->setSuperuser(false);
    	
    	$this->_em->persist($user);
    	$this->_em->flush($user);
    	
		$this->view->success = "New User Cretated Successfully.";
    }
    
    public function changePasswordAction()
    {
    	if(!$this->getRequest()->isPost())
    	{
    		return;
    	}
    	
    	$currentPass = $this->getRequest()->getParam('current');
    	$new1 = $this->getRequest()->getParam('newpass');
    	$new2 = $this->getRequest()->getParam('newpassagain');
    	
    	$user = Czechline_LoggedOnUSer::getUser();
    	
    	if(strcmp(hash_hmac('sha256', $currentPass, $user->getSalt()), $user->getPassword()) != 0)
    	{
    		$this->view->message = 'Current Password Invalid!';
    		return;
    	}
    	
    	if(strcmp($new1, $new2) != 0)
    	{
    		$this->view->message = 'New password mismatch!' ;
    		return;
    	}
    	
    	if(strlen($new1) < 8)
    	{
    		$this->view->message = 'New password must be at least 8 characters long';
    		return;
    	}
    	
    	$newSalt = substr(md5(mt_rand()), 0, 16);
    	$newPassHash = hash_hmac("sha256", $new1, $newSalt);
    	
    	
    	$user->setSalt($newSalt);
    	$user->setPassword($newPassHash);
    	
    	$this->_em->flush($user);
    	$this->view->success = 'Password successfully changed.';
    }
}