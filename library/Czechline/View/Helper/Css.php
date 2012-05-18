<?php

/**
 * Tento helper se pouziva v adminu. Zajisti, aby se automaticky do stranky pridal link na CSS soubor, 
 * ktery prislusi k danemu controlleru. Pokud takovy soubor neexistuje, nic se nelinkuje.
 * @author Petr Sobotka
 *
 */
class Czechline_View_Helper_Css
{
    function css()
    {
    	$frontController = Zend_Controller_Front::getInstance();
    	$request = $frontController->getRequest();
    	
    	$uri = '/css/admin/' . $request->getControllerName() . '.css';
    	
    	$filePath = DOCUMENTROOT_PATH . $uri;
    	
    	if(file_exists($filePath))
    	{
    		return $uri;
    	} else {
    		return null;
    	}
    }
}