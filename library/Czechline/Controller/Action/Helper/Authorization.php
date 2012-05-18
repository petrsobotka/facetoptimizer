<?php 

/**
 * Tento hleper zajistí, aby se uživatel nedostal k akcím, na 
 * která nemá práva.
 * 
 * Práva jsou uložena va databázi. Vlastního ověření se docílí tak,
 * že se pro přihlášeného uživatele sestaví kompletní Zend_Acl 
 * objekt se všemi Controllery jako Resources, 
 * uživatel i jeho skupina se do něj přidají jako Roles a všechny pravidla 
 * existující pro daného uživatele nebo jeho skupinu se přidají jako Rules.
 * 
 * @author Petr Sobotka
 *
 */

class Czechline_Controller_Action_Helper_Authorization
	extends Zend_Controller_Action_Helper_Abstract
{
	
	public function preDispatch()
	{
		/*
		 * Bootstrap je stejny pro vsechny moduly. Proto se to tu vola vzdy. Pokud ale nejsme v amdinu, jdem pryc
		 */
		if($this->getRequest()->getModuleName() != 'admin')
			return;
			
		$request = $this->getRequest();
		
		// pokud se snazim zobrazit hlášku o neautorizované akci, nebo uživatel snaží odhlásit, rovnou končíme, nemá smysl ověřovat práva
		if(($request->getActionName() == 'unauthorized' || $request->getActionName() == 'logout') && $request->getControllerName() == 'auth' && $request->getModuleName() == 'admin')
			return;
			
		$auth = Zend_Auth::getInstance();
		
		// tento helper by se mel vzdy provadet jen po prihlaseni
		if(!$auth->hasIdentity())
			return;
			//throw new Exception("Unauthenticated request for authorization.");
		
		$username  = $auth->getIdentity();
		
		// Nevím, zda toto je nejlepší způsob, jak se dostat k entity manageru...
		$aclService = new Model_Service_Acl_Standard(Zend_Controller_Front::getInstance()->getParam('bootstrap')->getResource('entityManager'));
		
		$acl = $aclService->setupAclForUser(Czechline_LoggedOnUser::getUser());
		
		if(!$acl->isAllowed($username, $request->getControllerName(), $request->getActionName()))
		{
            $request->setControllerName('auth');
            $request->setModuleName('admin');
            $request->setActionName('unauthorized')
                ->setDispatched(false);
		}
	}
}