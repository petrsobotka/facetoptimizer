<?php

class Czechline_AuthAdapter implements Zend_Auth_Adapter_Interface
{
	
	private $_username;
	private $_password;
	private $_entityManager;
	
    /**
     * Sets username and password for authentication
     *
     * @return void
     */
    public function __construct($username, $password)
    {
        $this->_username = $username;
        $this->_password = $password;
        
        /*
         * Nevím, zda toto je nejlepší způsob, jak se dostat k entity manageru...
         */
        $this->_entityManager = Zend_Controller_Front::getInstance()->getParam('bootstrap')->getResource('entityManager');
    }

    /**
     * Performs an authentication attempt
     *
     * @throws Zend_Auth_Adapter_Exception If authentication cannot
     *                                     be performed
     * @return Zend_Auth_Result
     */
    public function authenticate()
    {
        
    	$user = $this->_entityManager->getRepository('Model_User')->retrieveByUsername($this->_username);

        
        if ($user === null)
        {
        	return new Zend_Auth_Result(Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND, $this->_username);
        }
        
        if (strcmp($user->getPassword(), hash_hmac('sha256', $this->_password, $user->getSalt())) == 0)
        {
        	return new Zend_Auth_Result(Zend_Auth_Result::SUCCESS, $this->_username);
        }
        
        else return new Zend_Auth_Result(Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID, $this->_username);
    }
}