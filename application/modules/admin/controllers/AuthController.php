<?php

/**
 * Tento controller se stará o autentizaci užiavtele adminu.
 * @author Petr
 *
 */
class Admin_AuthController extends Zend_Controller_Action
{

	private $_em;
	
	/**
	 * 
	 * Logger k zachycení událostí o přihlašování.
	 * @var Czechline_Logger
	 */
	private $logger;
	
    public function init()
    {
        $bootstrap = $this->getInvokeArg('bootstrap');
        $this->_em = $bootstrap->getResource('entityManager');

        $this->logger = new Czechline_Logger(LOG_PATH);
    }
	
    /**
     * Tato akce zobrazí přihlašovací formulář a po jeho odeslání
     * ověří poskytnuté údaje a případně vytvoří pro uživatele session.
     */
	public function loginAction()
	{
		
		$this->_helper->layout()->disableLayout();
		
		if($this->getRequest()->isPost())
		{
			
			$username = $this->_request->getParam('username');
			$pass = $this->_request->getParam('pass');
			
			$auth = Zend_Auth::getInstance();
			$authAdapter = new Czechline_AuthAdapter($username, $pass);
			$result = $auth->authenticate($authAdapter);

			if (!$result->isValid()) {
				switch ($result->getCode()) {

					case Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND:
						$this->view->message = 'Invalid credentials!';
						$this->logger->auth("Přihlášení selhalo. Uživatelské jméno '" . $result->getIdentity() . "' nebylo nalezeno. Zadané heslo: '" . $pass . "'" );
						break;

					case Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID:
						$this->view->message = 'Invalid credentials!';
						$this->logger->auth("Přihlášení selhalo. Špatně zadané heslo pro uživatele '" . $username . "'");
						break;

					default:
						$this->view->message = 'Invalid credentials!';
						$this->logger->auth("Přihlášení selhalo. Zadané jméno '" . $username . "', zadané heslo: '" . $pass . "'.");
						break;
				}
				sleep(5);
				return;
			}
			$this->logger->auth("Přihlášen uživatel '" . $username . "'");
			$this->_redirect('/admin');
		}
	}
	
	/**
	 * Akce odhlásí uživatele.
	 */
	public function logoutAction()
	{
		$auth = Zend_Auth::getInstance();
		$this->logger->auth("Odhlášen uživatel '" . $auth->getIdentity() . "'");
		$auth->clearIdentity();
		$this->_redirect('/');
	}
	
	/**
	 * Akce, která se vykoná, pokud uživatel provede požadavek, k jehož vykonání nemá dostatečná práva.
	 */
	public function unauthorizedAction()
	{

		/**
		 * Pokud jde o AJAX pozadavek, nic nerenderujeme. 
		 */
		if($this->getRequest()->isXmlHttpRequest())
		{
			$this->getHelper('layout')->disableLayout();
			$this->getHelper('viewRenderer')->setNoRender();
			echo "Pro tuto operaci nemáte dostatečná práva.";
		}
		
		// tento radek je velmi dulezity, hlavne kvuli AJAX dotazum, ale spravny je i pro ostatni dotazy
		$this->getResponse()->setHttpResponseCode(401); // Unauthorized	 
	}
}