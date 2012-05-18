<?php 

class Model_FacetValueTest
	extends ControllerTestCase
{
    protected $_em;
	
	public function testInstantiation()
	{
		$this->assertInstanceOf('Model_FacetValue', new Model_FacetValue());
	}
}