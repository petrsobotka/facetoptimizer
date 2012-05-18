<?php 

class ControllerTestCase 
	extends Zend_Test_PHPUnit_ControllerTestCase
{
	
	public function setUp()
	{
		$this->bootstrap = new Zend_Application(
		    APPLICATION_ENV,
		    APPLICATION_PATH . '/config/application.ini'
		);
		parent::setUp();
	}
}