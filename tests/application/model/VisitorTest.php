<?php 

class Model_VisitorTest
	extends ControllerTestCase
{
    protected $_em;
	
	public function testInstantiation()
	{
		$this->assertInstanceOf('Model_Visitor', new Model_Visitor());
	}

	/*
	public function testNameSetterAndGetter()
	{
		$group = new Model_Group();
		$name = 'Administrators';
		$group->setName($name);
		$this->assertEquals($name, $group->getName());
	}

	public function testDescriptionSetterAndGetter()
	{
		$group = new Model_Group();
		$desc = 'Skupina největších geeků.';
		$group->setDescription($desc);
		$this->assertEquals($desc, $group->getDescription());
	}
	*/
}