<?php 

class Model_FacetPositionTest
	extends ControllerTestCase
{
    protected $_em;
	
	public function testInstantiation()
	{
		$this->assertInstanceOf('Model_FacetPosition', new Model_FacetPosition());
	}
}