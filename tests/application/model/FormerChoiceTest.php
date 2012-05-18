<?php 

class Model_FormerChoiceTest
	extends ControllerTestCase
{
    protected $_em;
	
	public function testInstantiation()
	{
		$this->assertInstanceOf('Model_FormerChoice', new Model_FormerChoice());
	}
}