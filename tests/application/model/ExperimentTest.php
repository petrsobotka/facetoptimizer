<?php 

class Model_ExperimentTest
	extends ControllerTestCase
{
    protected $_em;
	
	public function testInstantiation()
	{
		$this->assertInstanceOf('Model_Experiment', new Model_Experiment());
	}
}