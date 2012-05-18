<?php

class Api_IndexController extends Zend_Controller_Action
{


    public function indexAction()
    {
        $this->getHelper('layout')->disableLayout();
        $this->getHelper('viewRenderer')->setNoRender(true);

        echo "Please refer to " . $this->view->baseUrl() . "/api-specs for API documentation.";
    }
    
    
}