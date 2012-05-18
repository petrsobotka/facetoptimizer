<?php 

class Model_FacetTest
	extends ControllerTestCase
{
    protected $_em;
	
	public function testInstantiation()
	{
		$this->assertInstanceOf('Model_Facet', new Model_Facet());
	}
}