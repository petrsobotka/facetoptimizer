<?php 

/**
 * Tento hleper zajistí, aby všechny požadavky bez aktivní session 
 * byly přesměrovány na login page.
 * 
 * @author Petr Sobotka
 *
 */

class Czechline_Controller_Action_Helper_Authentication
	extends Zend_Controller_Action_Helper_Abstract
{
	
	public function preDispatch()
	{
		/*
		 * Bootstrap je stejny pro vsechny moduly. Proto se to tu vola vzdy. Pokud ale nejsme v adminu, jdem pryc
		 */
		if($this->getRequest()->getModuleName() != 'admin')
			return;
		
		
		$auth = Zend_Auth::getInstance();
		$request = $this->getRequest();
		
		// pokud jde o pozadavek na autentizaci, nebudeme zkouset, zda jsme autentizovani :-) doslo by k nekonecnemu presmerovani (!)
		if($request->getActionName() == 'login' && $request->getControllerName() == 'auth' && $request->getModuleName() == 'admin')
			return;
		
		// pokud nejsem prihlasen, presmeruji na prihlasovaci formular
		if (!$auth->hasIdentity()) {
            $request->setControllerName('auth');
            $request->setModuleName('admin');
            $request->setActionName('login')
                ->setDispatched(false);
		} else {
			// jsem prihlasen, nastavime si prihlaseneho uzivatele (vyzahneme z DB)
			$entityManager = Zend_Controller_Front::getInstance()->getParam('bootstrap')->getResource('entityManager');
			$user = $entityManager->getRepository('Model_User')->retrieveByUsername($auth->getIdentity());
			Czechline_LoggedOnUser::setUser($user);
		}
		
	}
}