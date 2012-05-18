<?php 

class Model_EventTypeTest
	extends ControllerTestCase
{
    protected $_em;
	
	public function testInstantiation()
	{
		$this->assertInstanceOf('Model_EventType', new Model_EventType());
	}
}