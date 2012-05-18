<?php

// kvůli Doctrine tady už musíme použít Namespaces
//use Doctrine\Common\ClassLoader;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\DBAL\Event\Listeners\MysqlSessionInit;
		
//require_once LIB_PATH . '/Doctrine/Common/ClassLoader.php';
//require_once LIB_PATH . '/Doctrine/ORM/Tools/Setup.php';

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

	protected function _initView()
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
	
	protected function _initAutoload()
	{  
		
        // loader na Doctrine třídy
		$loader = Zend_Loader_Autoloader::getInstance();
		$loader->registerNamespace('Doctrine');
		
		// loader na Czechline třídy (naše vlastní)
		$loader->registerNamespace('Czechline');
		
		// loader na modely
		$resourceLoader = new Zend_Loader_Autoloader_Resource(array(  
			'basePath' => APPLICATION_PATH ,  
			'namespace' => '',
			'resourceTypes' => array(
				'model' => array(
					'path' => '/model',
					'namespace' => 'Model',
				),
			),
		));
		
		$resourceLoader->setDefaultResourceType('model');
		
		return $resourceLoader;
	}

	protected function _initPlugins() 
	{

        // Ensure front controller instance is present, and fetch it
        $this->bootstrap('FrontController');
        $front = $this->getResource('FrontController');
        
	    $front->registerPlugin(new Czechline_Controller_Plugin_View());
	    $front->registerPlugin(new Czechline_Controller_Plugin_ModularLayout());
	    return $front;
	}
	
	protected function _initEntityManager()
	{

		// připravíme si Doctrine konfiguraci
		$config = new Configuration;
		// informace o entitách budeme brát z anotací v uvedeném adresáři
		$metadata = $config->newDefaultAnnotationDriver( MODEL_PATH );
		$config->setMetadataDriverImpl($metadata);
		// nastavení namespace a adresáře pro proxy třídy
		$config->setProxyNamespace('FacetOptimizer\Proxy');
		$config->setAutoGenerateProxyClasses(true);
		$config->setProxyDir(MODEL_PATH . '/Proxy');
		
		//nastavíme cacheování přes APC (vyžaduje zapnuté PHP extension APC, více na WIKI)
		if(APC_ON)
		{
			$cache = new \Doctrine\Common\Cache\ApcCache();
			$config->setQueryCacheImpl($cache);
			$config->setMetadataCacheImpl($cache);
		}
		
		// připravíme si konfiguraci databázového připojení
		$database = array(
		    'driver' => DB_DRIVER,
		    'host' => DB_HOST,
		    'dbname' => DB_DBNAME,
		    'user' => DB_USERNAME,
		    'password' => DB_PASSWORD,
			'charset' => DB_ENCODING
		);

		$entityManager = EntityManager::create($database, $config);
		$entityManager->getEventManager()->addEventSubscriber(new MysqlSessionInit('utf8', 'utf8_czech_ci'));
		
		return $entityManager;
	}
	
	// inicializace překladů pro validační hlášky
	protected function _initValidateTranslator()
	{
        $translator = new Zend_Translate(array(
                'adapter' => 'array',
                'content' => RESOURCES_PATH . '/languages',
                'locale'  => 'cs',
        ));
        Zend_Validate_Abstract::setDefaultTranslator($translator);
	}
	
	
	protected function _initAuthHelper()
	{
		// Helper Broker je stack, proto musíme nejrříve přidat helper, který se bude vyhodnocovat jako druhý
		/*
		Zend_Controller_Action_HelperBroker::addHelper(
		    new Czechline_Controller_Action_Helper_Authorization()
		);
		*/

		// nyní přidáme helper, který se bude spouštět jako první
		Zend_Controller_Action_HelperBroker::addHelper(
		    new Czechline_Controller_Action_Helper_Authentication()
		);
		
	}
	
	
	protected function _initMenuHelper()
	{
		Zend_Controller_Action_HelperBroker::addHelper(
		    new Czechline_Controller_Action_Helper_Menu()
		);
	}
	
	protected function _initRestRoute()
	{
		//getting an instance of zend front controller.
		$frontController = Zend_Controller_Front::getInstance();
		
		// router pro REST API
		$restRoute = new Zend_Rest_Route($frontController, array(), array('api'));
		$frontController->getRouter()->addRoute('rest', $restRoute);
		
		// staticka stranka /api-specs
		$route = new Zend_Controller_Router_Route(
				'api-specs',
				array(
						'module'     => 'public',
						'controller' => 'index',
						'action'     => 'api-specs'
				)
		);
		$frontController->getRouter()->addRoute('rest-api-specs', $route);
		
		// staticka stranka /faq
		$route = new Zend_Controller_Router_Route(
				'faq',
				array(
						'module'     => 'public',
						'controller' => 'index',
						'action'     => 'faq'
				)
		);
		$frontController->getRouter()->addRoute('faq', $route);
		
		// staticka stranka /installation
		$route = new Zend_Controller_Router_Route(
				'installation',
				array(
						'module'     => 'public',
						'controller' => 'index',
						'action'     => 'installation'
				)
		);
		$frontController->getRouter()->addRoute('installation', $route);
		
	}
}

