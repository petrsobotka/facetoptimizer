<?php 

class Czechline_Controller_Plugin_View extends Zend_Controller_Plugin_Abstract 
{

    public function dispatchLoopStartup(Zend_Controller_Request_Abstract $objRequest)
    {

    	$view = Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer')->view;
    	$view->setScriptPath(APPLICATION_PATH .'/modules/' . $objRequest->getModuleName().'/views/scripts');
    }
}
