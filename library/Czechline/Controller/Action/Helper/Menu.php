<?php 

/**
 * Tento hleper zajistí, aby se nám správně generovalo adminové menu
 * a vygeneruje ho do proměnných $menu a $submenu, které 
 * předá View. Oprostíme tak šablonu layout.html od zbytečného nastavování parametrů.
 * 
 * 
 * @author Petr Sobotka
 *
 */

class Czechline_Controller_Action_Helper_Menu
	extends Zend_Controller_Action_Helper_Abstract
{
	
	public function postDispatch()
	{
		/*
		 * Bootstrap je stejny pro vsechny moduly. Proto se to tu vola vzdy. Pokud ale nejsme v amdinu, jdem pryc
		 */
		if($this->getRequest()->getModuleName() != 'admin')
			return;
		
		$view = $this->getActionController()->view;
		$navigationViewHelper = $view->navigation();
		
		// nastavime parametry a vyrenderujeme hlavni menu
		$navigationViewHelper->menu()->setMaxDepth(0)->setMinDepth(0);
		$view->menu = $navigationViewHelper->menu()->render();
		
		// prenastavime parametry a vyrenderujeme submenu
		$navigationViewHelper->menu()->setMaxDepth(1)->setMinDepth(1)->setOnlyActiveBranch(true);
		$view->submenu = $navigationViewHelper->menu()->render();
	}
}