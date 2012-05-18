<?php 

class Czechline_Bootstrap_Resource_View extends Zend_Application_Resource_ResourceAbstract
{
	public function init()
	{
		
		
		$view = new Czechline_View_Smarty( null, array('compile_dir' => TEMP_PATH . '/templates_c'));
			//APPLICATION_PATH . '/modules/Admin/views/scripts'
		$viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper(
			'ViewRenderer'
		);
		$viewRenderer->setView($view)
             		 ->setViewSuffix('html');
  	
		return $view;
	}
}