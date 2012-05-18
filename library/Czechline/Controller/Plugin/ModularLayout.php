<?php 

class Czechline_Controller_Plugin_ModularLayout extends Zend_Controller_Plugin_Abstract 
{
 	public function preDispatch(Zend_Controller_Request_Abstract $request)
	{
		$layout = Zend_Layout::getMvcInstance();
		$layout->setLayoutPath( APPLICATION_PATH . '/modules/' . $request->getModuleName() . '/layouts' );
	}
}