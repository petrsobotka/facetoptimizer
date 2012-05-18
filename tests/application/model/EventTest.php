<?php 

class Model_EventTest
	extends ControllerTestCase
{
    protected $_em;
	
	public function testInstantiation()
	{
		$this->assertInstanceOf('Model_Event', new Model_Event());
	}
}